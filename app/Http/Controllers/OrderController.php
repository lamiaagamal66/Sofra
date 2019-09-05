<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\User;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = Order::where(function ($query) use($request){
            if ($request->input('keyword'))
            {
                $query->where(function ($query) use($request){
                    $query->where('address','like','%'.$request->keyword.'%');
                    
                    $query->orWhereHas('restaurant',function ($restaurant) use($request){
                        $restaurant->where('name','like','%'.$request->keyword.'%');
                    }); 
                    $query->orWhereHas('client',function ($client) use($request){
                        $client->where('name','like','%'.$request->keyword.'%');
                    }); 
                    // $query->orWhereHas('payment_type',function ($payment_type) use($request){
                    //     $payment_type->where('name','like','%'.$request->keyword.'%');
                    // }); 
                });
            }
            if ($request->input('status'))
            {
                $query->where('status',$request->status);
            }   
        })->paginate(20);

        return view('dashboard.orders.index',compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::with('restaurant','client')->findOrFail($id);
        return view('dashboard.orders.show',compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
