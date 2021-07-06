<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;
use App\Hole_time_work;
use App\Hole_booking;
use App\Hole_branch;
use App\Hole_media;
use App\Favorite;
use App\Shop;
use App\Hole;
use App\Product;
use App\Rate;
use App\User;
use App\Setting;

class ShopsController extends Controller
{
    public $personal_data = [];
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['all_shops','details', 'getOfferImage']]);

    }

    public function all_shops(Request $request) {
        $lang = $request->lang ;
        $user = auth()->user();
        $shops = Shop::select('id','name_'.$lang.' as title','logo','cover')
                        ->where('famous', '1')
                        ->where('status', 1)
                        ->orderBy('sort', 'asc')
                        ->get()
                        ->map(function($shops) use($user){
                            if($user == null){
                                $shops->favorite = false ;
                            }else{
                                $fav = Favorite::where('user_id', $user->id)->where('product_id', $shops->id)->where('type','shop')->first();
                                if($fav == null){
                                    $shops->favorite = false ;
                                }else{
                                    $shops->favorite = true ;
                                }
                            }
                            return $shops;
                        });

        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $shops, $request->lang );
        return response()->json($response , 200);
    }

    public function details(Request $request,$id) {
        $lang = $request->lang ;
        $user = auth()->user();
        Session::put('api_lang',$lang);
        if($lang == 'ar' ){
            $shop = Shop::select('id','cover','logo','name_ar as title')->find($id);
        }else{
            $shop = Shop::select('id','cover','logo','name_en as title')->find($id);
        }
        if($shop != null){
            if($user == null){
                $data['favorite'] = false ;
            }else{
                $fav = Favorite::where('user_id', $user->id)->where('product_id', $id)->where('type','shop')->first();
                if($fav == null){
                    $data['favorite'] = false ;
                }else{
                    $data['favorite'] = true ;
                }
            }
            $data['basic'] = $shop;
            if($lang == 'ar'){
                $data['categories'] = Category::select('id','image','title_ar as title')
                    ->where('shop_id',$id)
                    ->where('deleted',0)
                    ->get();
            }else{
                $data['categories'] = Category::select('id','image','title_en as title')
                    ->where('shop_id',$id)
                    ->where('deleted',0)
                    ->get();
            }
        }
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $data, $request->lang );
        return response()->json($response , 200);
    }
}
