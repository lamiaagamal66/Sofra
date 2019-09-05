<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\City;
use App\Models\Region;
use Response;

class RegionController extends Controller
{
    //
    public function index()
    {
        $regions = Region::paginate(20);
        return view('dashboard.regions.index',compact('regions'));
    }

    public function create()
    {
        $cities = City::pluck('name', 'id')->toArray();
        return view('dashboard.regions.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'city_id' => 'required'
        ]);
        Region::create($request->all());

        flash()->success('Successfully Added');
        return redirect(route('region.index'));
    }

    public function show($id)
    {
        
    }

    public function edit($id)
    {
        $model = Region::findOrFail($id);
        $cities = City::pluck('name', 'id')->toArray();
        return view('dashboard.regions.edit',compact('model','cities'));
    }

    public function update(Request $request , $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'city_id' => 'required'
        ]);
        $model = Region::findOrFail($id);
        $model->update($request->all());    

        flash()->success('Successfully Updated');
        return redirect(route('region.index'));

    }

    public function destroy($id)
    {
        $region = Region::findOrFail($id);
        $count = $region->restaurants()->count();
        if ($count > 0)
        {
            flash()->error('Can not delete region, there are restaurants in it');
        }
        $region->delete();

        flash()->error('Successfully Deleted');
        return redirect(route('region.index'));
    }
}
