<?php

namespace App\Services;

use App\Models\Setting;
use DB;
class SettingServices
{
    /**
     * Updates the settings in the database based on the given request data.
     *
     * @param $request The request data containing the store name, store address, store phone number one, and store phone number two.
     * @return array The response array containing the success status, message, and data (if successful).
     */
    public function updateSettings($request){

        $response = [
            'success' => false,
            'message' => '',
        ];

        $setting = Setting::orderBy('id','desc')->first();
        if(empty($setting)){
            $setting = new Setting;
        }

        $setting = DB::transaction(function () use ($request, $setting) {
            $setting->store_name = ($request->store_name != null) ? $request->store_name : $setting->store_name;
            $setting->store_address = ($request->store_address != null) ? $request->store_address : $setting->store_address;
            $setting->store_phone_number_one = ($request->store_phone_number_one != null) ? $request->store_phone_number_one : $setting->store_phone_number_one;
            $setting->store_phone_number_two = ($request->store_phone_number_two != null) ? $request->store_phone_number_two : $setting->store_phone_number_two;
            $setting->save();
            
            return $setting;
        });

        if(!empty($setting) && $setting->id){
            $response['success'] = true;
            $response['message'] = 'Berhasil mengubah setting';
            $response['data'] = $setting;
        } else {
            $response['success'] = false;
            $response['message'] = 'gagal mengubah setting';
        }

        return $response;
    }
}