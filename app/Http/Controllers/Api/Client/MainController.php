<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Models\Restaurant;
use App\Models\Product;

use App\Models\Token;
use App\Models\Client;
use App\Models\Order;
use DB;


class MainController extends Controller
{
   
    public function newOrder(Request $request)
    {
//        return $request->all();
        $validation = validator()->make($request->all(), [
            'restaurant_id'     => 'required|exists:restaurants,id',
            'products'             => 'required|array',
            'products.*'           => 'required|exists:products,id',
            'quantities'        => 'required|array',
            'notes'             => 'required|array',
            'address'           => 'required',
            'payment_type_id' => 'required|exists:payment_types,id',
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return responseJson(0, $validation->errors()->first(), $data);
        }

        $restaurant = Restaurant::find($request->restaurant_id);

        // restaurant closed
        if ($restaurant->status == 'closed') {
            return responseJson(0, 'عذرا المطعم غير متاح في الوقت الحالي');
        }

        $order = $request->user()->orders()->create([
                'restaurant_id'     => $request->restaurant_id,
                'note'              => $request->note,
                'status'             => 'pending', // db default
                'address'           => $request->address,
                'payment_type_id' => $request->payment_type_id,
          ]);

        $cost = 0;
        $delivery_cost = $restaurant->delivery_cost;

        if ($request->has('products')) {
            $counter = 0;
            foreach ($request->products as $productId) {
                $product = Product::find($productId);
                $order->products()->attach([
                $productId => [
                    'quantity' => $request->quantities[$counter],
                    'salary'    => $product->salary,
                    'note'     => $request->notes[$counter],
                ]
               ]);
                $cost += ($product->salary * $request->quantities[$counter]);
                $counter++;
            }
        }

        // minimum cost
        if ($cost >= $restaurant->minimum_cost) {
            $total = $cost + $delivery_cost; // 200 SAR
            $commission = settings()->commission * $cost; // 20 SAR  // 10 // 0.1  // $total; edited to remove delivery cost from percent.
            $net = $total - settings()->commission;
            $update = $order->update([
                     'cost'          => $cost,
                     'delivery_cost' => $delivery_cost,
                     'total_cost'         => $total,
                     'commission'    => $commission,
                     'net'           => $net,
                 ]);

            $notification = $restaurant->notifications()->create([
                    'title' =>'لديك طلب جديد',
                    'content' =>$request->user()->name .  'لديك طلب جديد من العميل ',
                    'action' =>  'new-order',
                    'order_id' => $order->id,
            ]);
            $tokens = $restaurant->tokens()->where('token', '!=' ,null)->pluck('token')->toArray();
            //info("tokens result: " . json_encode($tokens));
            if(count($tokens))
            {
                public_path();
                $title = $notification->title;
                $content = $notification->content;
                $data =[
                    'order_id' => $order->id,
                    'user_type' => 'restaurant',
                ];
                $send = notifyByFirebase($title , $content , $tokens,$data);
                info("firebase result: " . $send);

            }
            /* notification */
            $data = [
                'order' => $order->fresh()->load('products','restaurant.region','restaurant.categories','client')
            ];
            return responseJson(1, 'تم الطلب بنجاح', $data);
        } else {
            $order->products()->delete();
            $order->delete();
            return responseJson(0, 'الطلب لابد أن لا يكون أقل من ' . $restaurant->minimum_cost . ' ريال');
        }
    }

    
    public function orders(Request $request)
    {
        $orders = Order::where(function ($order) use ($request) {
            if ($request->has('status') && $request->status == 'completed') {
                $order->where('status', '!=', 'pending');
            } elseif ($request->has('status') && $request->status == 'current') {
                $order->where('status', '=', 'pending');
            }
        })->with('products','restaurant.region','restaurant.categories','client')->latest()->paginate(20);
        return responseJson(1, 'تم التحميل', $orders);
    }

    // public function order(Request $request)
    // {
    //     $order = Order::find($request->order_id);
    //         if (!$order) 
    //         {
    //             return responseJson(0, '404 no order found');
    //         }
    //         if ($request->user()->notifications()->where('order_id',$order->id)->first())
    //         {
    //             $request->user()->notifications()->where('order_id',$order->id)->update([
    //           'is_read' => 1  ]);
            
    //         }
    //     return responseJson('1','success',$order);
    // }


    public function confirmOrder(Request $request)
    {
        $order = Order::find($request->order_id);
        if (!$order) {
            return responseJson(0, 'لا يمكن الحصول على البيانات');
        }
        if ($order->status != 'accepted') {
            return responseJson(0, ' لم يتم قبول الطلب');
        }
        $order->update(['status' => 'delivered']);
        $restaurant = $order->restaurant;
        $restaurant->notifications()->create([
                 'title'      => ' تأكيد توصيل طلبك من العميل',
                 'content'    => ' تأكيد التوصيل للطلب رقم ' . $request->order_id . ' للعميل',
                 'order_id'   => $request->order_id,
             ]);

        $tokens = $restaurant->tokens()->where('token', '!=', '')->pluck('token')->toArray();
        if(count($tokens))
        {
            $title = $notification->title;
            $content = $notification ->content;
            $data = [
                'action' => 'delivered',
                'order_id' => $request->order_id,
            ];
            $send = notifyByFirebase($title , $content , $tokens , $data);
            info("firebase result: " . $send);
        }
        return responseJson(1, 'تم تأكيد الاستلام');
    }

    public function declineOrder(Request $request)
    {
        $order = Order::find($request->order_id);
        if (!$order) {
            return responseJson(0, 'لا يوجد بيانات');
        }
        if ($order->status != 'accepted') {
            return responseJson(0, ' لم يتم قبول الطلب');
        }
       
        $order->update(['status' => 'declined']);
        $restaurant = $order->restaurant;
        $restaurant->notifications()->create([
             'title'      => 'تم رفض توصيل الطلب',
             'content'    => 'تم رفض توصيل الطلب رقم' . $request->order_id . ' للعميل',
             'order_id'   => $request->order_id,
         ]);

        $tokens = $restaurant->tokens()->where('token', '!=', '')->pluck('token')->toArray();
        if(count($tokens))
        {
            $title = $notification->title;
            $content = $notification ->content;
            $data = [
                'action' => 'declined',
                'order_id' => $request->order_id,
            ];
            $send = notifyByFirebase($title , $content , $tokens , $data);
            info("firebase result: " . $send);
        }
        return responseJson(1, 'تم رفض استلام الطلب');
    }



    public function review(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'rate'          => 'required',
            'comment'       => 'required',
            'restaurant_id' => 'required|exists:restaurants,id',

        ]);
        if ($validation->fails()) {
            return responseJson(0, $validation->errors()->first(), $validation->errors());
        }
        $restaurant = Restaurant::find($request->restaurant_id);
        if (!$restaurant) {
            return responseJson(0, 'لا يوجد بيانات');
        }
        $request->merge(['client_id' => $request->user()->id]);
      
        $review = $restaurant->reviews()->create($request->all());
        return responseJson(1, 'تم التقييم',['review' => $review->load('client','restaurant')]);

    }
    
}