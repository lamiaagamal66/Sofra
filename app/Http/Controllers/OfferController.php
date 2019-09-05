<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offer;
use App\Models\Restaurant;
use Image;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $offers = Offer::where(function ($query) use($request){
            if ($request->input('keyword'))
            {
                $query->where(function ($query) use($request){
                    $query->orWhereHas('restaurant',function ($restaurant) use($request){
                        $restaurant->where('name','like','%'.$request->keyword.'%');
                    }); 
                });
            }
        })->paginate(20);
        return view('dashboard.offers.index',compact('offers'));
    }

    /**
     * Show the form for creating a new resource. 
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $restaurants = Restaurant::pluck('name', 'id')->toArray();
        return view('dashboard.offers.create', compact('restaurants'));
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' =>'required',
            'description' =>'required',
            'starting_at' =>'required',
            'ending_at' =>'required',
            'restaurant_id' =>'required'
        ],[
            
        ]);
        $offer = Offer::create($request->all());
        if ($request->hasFile('image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/images/'; // upload path
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $image->move($destinationPath, $name); // uploading file to given path
            $offer->image = 'uploads/images/' . $name;
        }
        $offer->save();
  
        flash()->success('Offer Successfully Added');
        return redirect(route('offer.index'));
    }

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
        $model = Offer::findOrFail($id);
        $restaurants = Restaurant::pluck('name', 'id')->toArray();
        return view('dashboard.offers.edit', compact('model','restaurants'));
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' =>'required',
            'description' =>'required',
            'starting_at' =>'required',
            'ending_at' =>'required',
            'restaurant_id' =>'required',
        ]);
        $offer = Offer::findOrFail($id);
        $offer->update($request->all());
        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(100, 100)->save( public_path('/uploads/' . $filename ) );
            $offer->image = $filename;
            $offer->save();
        }
  
        flash()->success('Successfully Updated');
        return redirect(route('offer.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $offer = Offer::findOrFail($id);
        $offer->delete();

        flash()->error('Successfully Deleted');
        return redirect(route('offer.index'));
    }
}
