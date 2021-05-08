<?php
namespace App\Http\Controllers\Admin\Ads;
use App\Coach;
use App\Hole;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use App\Ad;
use App\User;
use App\Product;

class AdController extends AdminController{

    // type get
    public function AddGet(){
        $data['users'] = User::orderBy('created_at', 'desc')->get();
        $data['halls'] = Hole::where('deleted','0')->orderBy('sort' , 'asc')->get();
        $data['coaches'] = Coach::where('is_confirm','accepted')->where('deleted','0')->get();
        return view('admin.ads.ad_form', ["data" => $data]);
    }

    // type post
    public function AddPost(Request $request){
        $image_name = $request->file('image')->getRealPath();
        Cloudder::upload($image_name, null, array("timeout" => 600000000));
        $imagereturned = Cloudder::getResult();
        $image_id = $imagereturned['public_id'];
        $image_format = $imagereturned['format'];
        $image_new_name = $image_id.'.'.$image_format;
        $ad = new Ad();
        $ad->image = $image_new_name;
        $ad->title_ar = $request->title_ar;
        $ad->title_en = $request->title_en;
        $ad->desc_ar = $request->desc_ar;
        $ad->desc_en = $request->desc_en;
        $ad->type = $request->type;
        if($request->type == 'link'){
            $ad->content = $request->content;
        }else if($request->type == 'hall'){
            $ad->content = $request->hall;
        }else if($request->type == 'coach'){
            $ad->content = $request->coach;
        }
        $ad->save();
        session()->flash('success', trans('messages.added_s'));
        return redirect('admin-panel/ads/show');
    }
    // get all ads
    public function show(Request $request){
        $data['ads'] = Ad::orderBy('id' , 'desc')->get();
        return view('admin.ads.ads' , ['data' => $data]);
    }

    // get edit page
    public function EditGet(Request $request){
        $data['ad'] = Ad::find($request->id);
        $data['users'] = User::orderBy('created_at', 'desc')->get();
        $data['halls'] = Hole::where('deleted','0')->orderBy('sort' , 'asc')->get();
        $data['coaches'] = Coach::where('is_confirm','accepted')->where('deleted','0')->get();
        if ($data['ad']['type'] == 'id') {
            $data['product'] = Product::find($data['ad']['content']);
        }else {
            $data['product'] = [];
        }
        return view('admin.ads.ad_edit' , ['data' => $data]);
    }

    // post edit ad
    public function EditPost(Request $request){
        $ad = Ad::find($request->id);
        if($request->file('image')){
            $image = $ad->image;
            $publicId = substr($image, 0 ,strrpos($image, "."));
            // Cloudder::delete($publicId);
            $image_name = $request->file('image')->getRealPath();
            Cloudder::upload($image_name, null);
            $imagereturned = Cloudder::getResult();
            $image_id = $imagereturned['public_id'];
            $image_format = $imagereturned['format'];
            $image_new_name = $image_id.'.'.$image_format;
            $ad->image = $image_new_name;
        }
        $ad->title_ar = $request->title_ar;
        $ad->title_en = $request->title_en;
        $ad->desc_ar = $request->desc_ar;
        $ad->desc_en = $request->desc_en;
        $ad->type = $request->type;
        if($request->type == 'link'){
            $ad->content = $request->content;
        }else if($request->type == 'hall'){
            $ad->content = $request->hall;
        }else if($request->type == 'coach'){
            $ad->content = $request->coach;
        }
        $ad->save();
        session()->flash('success', trans('messages.updated_s'));
        return redirect('admin-panel/ads/show');
    }

    public function details(Request $request){
        $data['ad'] = Ad::find($request->id);
        if ($data['ad']['type'] == 'id') {
            $data['product'] = Product::findOrFail($data['ad']['content']);
        }else {
            $data['product'] = [];
        }
        return view('admin.ads.ad_details' , ['data' => $data]);
    }

    public function delete(Request $request){
        $ad = Ad::find($request->id);
        if($ad){
            $ad->delete();
        }
        return redirect('admin-panel/ads/show');
    }

    public function fetch_products($userId) {
        $row = User::findOrFail($userId)->products;
        $data = json_decode($row);

        return response($data, 200);
    }
}
