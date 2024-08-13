<?php

namespace App\Http\Controllers\Application\Web\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\SettingServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setting = Setting::orderBy('id', 'desc')->first();
        // dd($setting);
        return view('application.setting.edit',[
            'active_page' => 'settings',
            'setting' => $setting
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Validate
        $validation_rules = [
            'store_name' => 'required|unique:settings,store_name,NULL,id,deleted_at,NULL',
            'store_address' => 'required',
            'store_phone_number_one' => 'required',
        ];
        $this->validate($request,$validation_rules);

        $settingServices = new SettingServices();
        $saveSetting = $settingServices->updateSettings($request);

        if($saveSetting['success']){
            return redirect()->back()->with('success_message', $saveSetting['message']);
        } else {
            return redirect()->back()->with('error_message', $saveSetting['message']);
        }
    }
}
