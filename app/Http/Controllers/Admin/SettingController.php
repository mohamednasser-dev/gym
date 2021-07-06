<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use App\Setting;

class SettingController extends AdminController{

    // get setting
    public function GetSetting(){
        $data['setting'] = Setting::find(1);
        return view('admin.setting' , ['data' => $data]);
    }

    // post setting
    public function PostSetting(Request $request){
        $setting = Setting::find(1);
        if($request->file('logo')){
            $logo = $setting->logo;
            $publicId = substr($logo , 0 , strrpos($logo , "."));
            Cloudder::delete($publicId);
            $logo_name = $request->file('logo')->getRealPath();
            Cloudder::upload($logo_name , null);
            $logoreturned = Cloudder::getResult();
            $logo_id = $logoreturned['public_id'];
            $logo_format = $logoreturned['format'];
            $logo_new_name = $logo_id.'.'.$logo_format;
            $setting->logo = $logo_new_name;
        }
        if($request->file('offer_image')){
            $offer_image = $setting->offer_image;
            $publicId = substr($offer_image , 0 , strrpos($offer_image , "."));
            Cloudder::delete($publicId);
            $logo_name = $request->file('offer_image')->getRealPath();
            Cloudder::upload($logo_name , null);
            $logoreturned = Cloudder::getResult();
            $logo_id = $logoreturned['public_id'];
            $logo_format = $logoreturned['format'];
            $logo_new_name = $logo_id.'.'.$logo_format;
            $setting->offer_image = $logo_new_name;
        }
        $setting->app_name_en = $request->app_name_en;
        $setting->app_name_ar = $request->app_name_ar;
        $setting->email = $request->email;
        $setting->phone = $request->phone;
        $setting->address_en = $request->address_en;
        $setting->address_ar = $request->address_ar;
        $setting->app_name_ar = $request->app_name_ar;
        $setting->facebook = $request->facebook;
        $setting->youtube = $request->youtube;
        $setting->twitter = $request->twitter;
        $setting->instegram = $request->instegram;
        $setting->snap_chat = $request->snap_chat;
        $setting->watsapp = $request->watsapp;
        $setting->map_url = $request->map_url;
        $setting->latitude = $request->latitude;
        $setting->longitude = $request->longitude;
        $setting->fax = $request->fax;
        $setting->post_address = $request->post_address;
		$setting->save();
        return  back();
    }
}
