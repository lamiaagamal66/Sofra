<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $restaurants = Restaurant::where(function ($query) use ($request) {

            if ($request->input('keyword'))
            {
                $query->where(function ($query) use($request){
                    $query->where('name','like','%'.$request->keyword.'%');

                    $query->orWhereHas('region',function ($region) use($request){
                        $region->where('name','like','%'.$request->keyword.'%');
                    });
                });
            }
            if ($request->input('status'))
            {
                $query->where('status',$request->status);
            }
            if ($request->has('city_id')) {
                $query->whereHas('region',function ($q2) use($request){
                    // search in restaurant region "Region" Model
                    $q2->whereCityId($request->city_id);
                });
            }

        })->with('region.city')->latest()->paginate(20);
        return view('dashboard.restaurants.index', compact('restaurants'));
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
        $restaurant = Restaurant::findOrFail($id);
        return view('dashboard.restaurants.show',compact('restaurant'));
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
        $restaurant = Restaurant::findOrFail($id);
        if (count($restaurant->orders) > 0) {
            flash()->error('Can not delete restaurant, there are orders related to it');
        }

        $restaurant->delete();
        flash()->error('Successfully Deleted');
        return redirect(route('restaurant.index'));
    }

    public function activate($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->update(['is_active'=> 1]);
        flash()->success('Activated');
        return back();
    } 

    public function deactivate($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->update(['is_active'=> 0]);
        flash()->success('De-Activated');
        return back();
    }
}
