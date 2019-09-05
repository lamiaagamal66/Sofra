<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentType;

class PaymentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payment_types = PaymentType::paginate(20);
        return view('dashboard.payment_types.index',compact('payment_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new PaymentType;
        return view('dashboard.payment_types.create',compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required'
        ]);
        PaymentType::create($request->all());
        flash()->success('Payment Type added Successfully');
        return redirect(route('payment-type.index'));    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = PaymentType::findOrFail($id);
        return view('dashboard.payment_types.edit',compact('model'));
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
        $this->validate($request,[
            'name' => 'required',
        ]);
        PaymentType::findOrFail($id)->update($request->all());
        flash()->success('Successfully Updated');
        return redirect(route('payment-type.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = PaymentType::findOrFail($id);
        $count = $record->orders()->count();
        if ($count > 0)
        {
            flash()->error('Can not delete PaymentType, there are orders related to it');
        }
        $record->delete();
        flash()->error('Successfully Deleted');
      return redirect(route('payment-type.index'));
        
    
    }
}
