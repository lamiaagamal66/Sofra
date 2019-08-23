<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Contact;
use App\Models\Item;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Region;
use App\Models\Restaurant;
use App\Models\Section;
use DB;
use Illuminate\Http\Request;
use Log;

class MainController extends Controller
{
    public function cities(Request $request)
    {
        $cities = City::paginate(10);
        return responseJson(1,'تم التحميل',$cities);
    }

    public function regions(Request $request)
    {
        $regions = Region::where(function($q) use($request){
            if ($request->has('name')){
                $q->where('name','LIKE','%'.$request->name.'%');
            }
        })->where('city_id',$request->city_id)->paginate(10);
        return responseJson(1,'تم التحميل',$regions);
    }

    public function categories()
    {
        $categories = Category::all();
        return responseJson(1,'تم التحميل',$categories);
    }

    public function restaurants(Request $request)
    {
        $restaurants = Restaurant::where(function($q) use($request) {
            if ($request->has('keyword'))
            {
                $q->where(function($query) use($request){
                    $query->where('name','LIKE','%'.$request->keyword.'%');
                });
            } 
            if ($request->has('city_id'))
            {
                $q->where('district',function($query) use ($request){
                    $query->where('districts.city_id',$request->city_id);
                });
            }
        })->paginate(10);
        return responseJson(1,'تم التحميل',$restaurants);
    }

    public function restaurant(Request $request)
    {
        $restaurant = Restaurant::with('region','categories')->is_active()->findOrFail($request->restaurant_id);

        return responseJson(1,'تم التحميل',$restaurant);

    }

    public function products(Request $request)
    {
        $products = Product::where('restaurant_id',$request->restaurant_id)->paginate(20);
        return responseJson(1,'تم التحميل',$products);
    }

    public function product(Request $request)
    {
        $product = Product::find($request->product_id);
        return responseJson(1,'تم التحميل',$product);
    }

    public function paymentTypes()
    {
        $methods = PaymentType::all();
        return responseJson(1,'تم التحميل',$methods);
    }

    public function offers(Request $request)
    {
        $offers = Offer::latest()->paginate(20);
        return responseJson(1,'',$offers);
    }

    public function offer(Request $request)
    {
        $offer = Offer::with('restaurant')->find($request->offer_id);
        if (!$offer)
        {
            return responseJson(0,'no data');
        }
        return responseJson(1,'',$offer);
    }
   
    public function contact(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'type' => 'required|in:complaint,suggestion,inquiry',
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'message' => 'required'
        ]);

        if ($validation->fails()) 
        {
            return responseJson(0 ,$validation->errors()->first() ,$validation->errors());
        }

        $contact = Contact::create($request->all());

        Mail::to('lamiaagamal66@gmail.com')
        ->send(new ContactUs($contact));
        return responseJson(1,'تم الارسال بنجاح');

    }

    public function reviews(Request $request)
    {
        $restuarant = Restaurant::find($request->restaurant_id);
        if (!$restuarant)
        {
            return responseJson(0,'no data');
        }
        $reviews = $restuarant->reviews()->paginate(10);
        return responseJson(1,'',$reviews);
        
    }

    
    public function settings()
    {
        return responseJson(1,'',settings());
    }


}