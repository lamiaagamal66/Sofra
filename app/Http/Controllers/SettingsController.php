<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function view(Setting $model)
    {
        if ($model->all()->count() > 0)
        {
            $model = Setting::find(1);
        }
        return view('dashboard.settings.view', compact('model'));
    }

    public function update(Request $request)
    {
        if (Setting::all()->count() > 0)
        {
            Setting::find(1)->update($request->all());
        }else{
            Setting::create($request->all());
        }

        flash()->success('Successfully Updated');
        return back();
    }
}
