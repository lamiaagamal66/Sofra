<?php

namespace App\Http\Controllers\Api\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Restaurant;

class MainController extends Controller
{
    //////////////////////////////////// Products Apis ////////////////////////////////////////////
    public function products()
    {
        $products = Product::paginate(20);
        return responseJson(1,'تم التحميل',$products);
    }

    public function newProduct(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'name' => 'required',
            'salary' => 'required|numeric',
            'prepare_time' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validation->fails()) 
        {
            return responseJson(0,$validation->errors()->first(),$validation->errors());
        }
        $product = $request->user()->products()->create($request->all());
        if ($request->hasFile('image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/productImages/'; // upload path
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $image->move($destinationPath, $name); // uploading file to given path
            $product->update(['image' => 'uploads/productImages/' . $name]);
        }

        return responseJson(1,'تم الاضافة بنجاح',$product);
    }

    public function updateProduct(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'name' => 'required',
            'salary' => 'required|numeric',
            'prepare_time' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validation->fails()) 
        {
            return responseJson(0,$validation->errors()->first(),$validation->errors());
        }

        $product = $request->user()->products()->find($request->product_id);
        if (!$product)
        {
            return responseJson(0,'لا يمكن الحصول على البيانات');
        }
        $product->update($request->all());

        if ($request->hasFile('image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/productImages/'; // upload path
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $image->move($destinationPath, $name); // uploading file to given path
            $product->update(['image' => 'uploads/productImages/' . $name]);
        }

        return responseJson(1,'تم التعديل بنجاح',$product);
    }


    public function deleteProduct(Request $request)
    {
        $product = $request->user()->products()->find($request->product_id);
        if (!$product)
        {
            return responseJson(0,'لا يمكن الحصول على البيانات');
        }
        $product->delete();
        return responseJson(1,'تم الحذف بنجاح');
    }


    //////////////////////////////////// Offers Apis ////////////////////////////////////////////
    public function offers()
    {
        $offers = Offer:: with('restaurant')->latest()->paginate(20);
        return responseJson(1,'',$offers);
    }

    public function newoffer(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'starting_at' => 'required|date_format:Y-m-d',
            'ending_at' => 'required|date_format:Y-m-d',
        ]);
        if ($validation->fails()) 
        {
            return responseJson(0,$validation->errors()->first(),$validation->errors());
        }
        $offer = $request->user()->offers()->create($request->all());
        if ($request->hasFile('image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/offerImages/'; // upload path
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $image->move($destinationPath, $name); // uploading file to given path
            $offer->update(['image' => 'uploads/offerImages/' . $name]);
        }

        return responseJson(1,'تم الاضافة بنجاح',$offer);
    }

    public function updateOffer(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'name' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'starting_at' => 'required|date_format:Y-m-d',
            'ending_at' => 'required|date_format:Y-m-d',
        ]);

        if ($validation->fails()) 
        {
            $data = $validation->errors();
            return responseJson(0,$validation->errors()->first(),$data);
        }

        $offer = $request->user()->offers()->find($request->offer_id);
        if (!$offer)
        {
            return responseJson(0,'لا يوجد بيانات');
        }
        $offer->update($request->all());
        if ($request->hasFile('image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/offerImages/'; // upload path
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $image->move($destinationPath, $name); // uploading file to given path
            $offer->update(['image' => 'uploads/offerImages/' . $name]);
        }

        return responseJson(1,'تم التعديل بنجاح',$offer);
    }

    public function deleteOffer(Request $request)
    {
        $offer = $request->user()->offers()->find($request->offer_id);
        if (!$offer)
        {
            return responseJson(0,'لا يوجد بيانات');
        }
        $offer->delete();
        return responseJson(1,'تم الحذف بنجاح');
    }


    //////////////////////////////////// Orders Apis ////////////////////////////////////////////
    public function orders(Request $request)
    {
        $orders = $request->user()->orders()->where(function($order) use($request){
            if ($request->has('status') && $request->status == 'completed')
            {
                $order->where('status' , '!=' , 'pending');
            } 
            elseif ($request->has('status') && $request->status == 'current')
            {
                $order->where('status' , '=' , 'accepted');
            }
            elseif ($request->has('status') && $request->status == 'pending')
            {
                $order->where('status' , '=' , 'pending');
            }
        })->with('client','products','restaurant.region','restaurant.categories')->latest()->paginate(20);
        return responseJson(1,' تم بنجاح ',$orders);
    }

    public function acceptOrder(Request $request)
    {
        $order= Order::find($request->order_id);
        if (!$order)
        {
            return responseJson(0,'لا يمكن الحصول على بيانات الطلب');
        }
        if ($order->status == 'accepted')
        {
            return responseJson(1,'تم قبول الطلب');
        }
        if ($user->is_active == 0) {
            return responseJson(0, 'لم يتم تفعيل حسابك بعد');
        }
        if (($user->total_commissions - $user->total_payments) > 400) {
            $data = [
                'api_token' => $user->api_token,
                'user'      => $user->load('city'),
            ];
            return responseJson(
                -1,
                'تم ايقاف حسابك مؤقتا الى حين سداد العموله لوصولها للحد الاقصى ',
                $data
            );
        } else {

            $order->update(['status' => 'accepted']);
            $client = $order->client;
            $client->notifications()->create([
                'title' => 'تم الموافقه على طلبك',
                'content' => 'تم الموافقه على الطلب رقم  '.$request->order_id,
                'order_id' => $request->order_id,
                'action' => "accepted",
            ]);
    
            $tokens = $client->tokens()->where('token','!=',null)->pluck('token')->toArray();
            if(count($tokens))
            {
                $title = $notification->title;
                $content = $notification ->content;
                $data = [
                    'action' => 'accepted',
                    'order_id' => $request->order_id,
                    'restaurant_id' => $request->user()->id,
                ];
                $send = notifyByFirebase($title , $content , $tokens , $data);
                info("firebase result: " . $send);
            }
            return responseJson(1,'تم قبول الطلب');
        } 
        
    }

    public function rejectOrder(Request $request)
    {
        $order= Order::find($request->order_id);
        if (!$order)
        {
            return responseJson(0,'لا يمكن الحصول على بيانات الطلب');
        }
        if ($order->status == 'rejected')
        {
            return responseJson(1,'تم رفض الطلب');
        }
        if ($user->is_active == 0) {
            return responseJson(0, 'لم يتم تفعيل حسابك بعد');
        }
        if (($user->total_commissions - $user->total_payments) > 400) {
            $data = [
                'api_token' => $user->api_token,
                'user'      => $user->load('city'),
            ];
            return responseJson(
                -1,
                'تم ايقاف حسابك مؤقتا الى حين سداد العموله لوصولها للحد الاقصى ',
                $data
            );
        } else {

            $order->update(['status' => 'rejected']);
        $client = $order->client;
        $client->notifications()->create([
            'title' => 'تم رفض طلبك',
            'content' => 'تم رفض الطلب رقم '.$request->order_id,
            'order_id' => $request->order_id,
            'action' =>'rejected',
        ]);

        $tokens = $client->tokens()->where('token','!=',null)->pluck('token')->toArray();
        if(count($tokens))
        {
            $title = $notification->title;
            $content = $notification ->content;
            $data = [
                'action' => 'rejected',
                'order_id' => $request->order_id,
                'restaurant_id' => $request->user()->id,
            ];
            $send = notifyByFirebase($title , $content , $tokens , $data);
            info("firebase result: " . $send);
        }
        return responseJson(1,'تم رفض الطلب');
        
        }
    }

    public function confirmOrder(Request $request)
    {
        $order = Order:: find($request->order_id);
        if (!$order)
        {
            return responseJson(0,'لا يمكن الحصول على بيانات الطلب');
        }
        if ($order->status != 'accepted')
        {
            return responseJson(0,' لم يتم قبول الطلب');
        }
        $order->update(['status' => 'delivered']);
        $client = $order->client;
        $client->notifications()->create([
            'title' => 'تم التأكد من وصول طلبك',
            'content' =>  'تم التأكد من وصول الطلب رقم'.$request->order_id,
            'order_id' => $request->order_id,
            'action' => 'delivered',
        ]);

        $tokens = $client->tokens()->where('token','!=','')->pluck('token')->toArray();
        if(count($tokens))
        {
            $title = $notification->title;
            $content = $notification ->content;
            $data = [
                'action' => 'delivered',
                'order_id' => $request->order_id,
                'restaurant_id' => $request->user()->id,
            ];
            $send = notifyByFirebase($title , $content , $tokens , $data);
            info("firebase result: " . $send);
        }
        return responseJson(1,'تم تأكيد الاستلام');
    }

    public function status(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'status' => 'required|in:open,closed'
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return responseJson(0,$validation->errors()->first(),$data);
        }

        $request->user()->update(['status' => $request->status]);

        return responseJson(1,'',$request->user());

    }

    public function commissions(Request $request)
    {
        $count = $request->user()->orders()->where('status','accepted')->where(function($q){
            $q->where('status','delivered');
        })->count();

        $total = $request->user()->orders()->where('status','accepted')->where(function($q){
            $q->where('status','delivered');
        })->sum('total');

        $commissions = $request->user()->orders()->where('status','accepted')->where(function($q){
            $q->where('status','delivered');
        })->sum('commission');

        $payments = $request->user()->payments()->sum('amount');

        $net_commissions = $commissions - $payments;

        $commission = settings()->commission;

        return responseJson(1,'',compact('count','total','commissions','payments','net_commissions','commission'));
    }


}    