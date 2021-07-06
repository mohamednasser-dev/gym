<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product_feature;
use App\Product_view;
use App\ProductImage;
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
        $shop = Shop::select('id','cover','logo','name_'.$lang.' as title')->find($id);
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
            $first_cat_id = Category::select('id','image','title_'.$lang.' as title')
                ->where('deleted',0)
                ->orderBy('created_at')
                ->first();
            $data['categories'] = Category::select('id','image','title_'.$lang.' as title')
                ->where('deleted',0)
                ->orderBy('created_at')
                ->get()->map(function($data) use($first_cat_id){
                    if($data->id == $first_cat_id->id){
                        $data->selected = true ;
                    }else{
                        $data->selected = false ;
                    }
                    return $data;
                });


            $data['products'] = Product::select('id','title_'.$lang.' as title','final_price','price_before_offer','offer','offer_percentage','category_id')
                ->where('store_id',$id)
                ->where('category_id',$first_cat_id->id)
                ->where('deleted',0)
                ->get()->map(function($data) use($user){
                    $data->image = $data->mainImage->image;
                    if($user == null){
                        $data->favorite = false ;
                    }else{
                        $fav = Favorite::where('user_id', $user->id)->where('product_id', $data->id)->where('type','product')->first();
                        if($fav == null){
                            $data->favorite = false ;
                        }else{
                            $data->favorite = true ;
                        }
                    }
                    return $data;
                })->makeHidden('mainImage');
        }
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $data, $request->lang );
        return response()->json($response , 200);
    }

    public function product_details(Request $request){

        $user = auth()->user();
        $lang = $request->lang;
        Session::put('lang',$lang);
        $data = Product::with('images')->select('id','title_'.$lang.' as title','description_'.$lang.' as description','remaining_quantity','final_price','price_before_offer','offer','offer_percentage','category_id')
            ->find($request->id);
//        $data->images = $data->images ;

//        $features = Product_feature::where('product_id',$request->id)
//            ->select('id','type','product_id','target_id','option_id')
//            ->get();

//        $feature_data = null ;
//        foreach ($features as $key => $feature) {
//
//            $feature_data[$key]['image'] = $feature->Option->image;
//            if ($feature->type == 'manual') {
//                $feature_data[$key]['title'] = $feature->Option->title . ' : ' . $feature->target_id;
//            } else if ($feature->type == 'option') {
//                $feature_data[$key]['title'] = $feature->Option->title . ' : ' . $feature->Option_value->value;
//            }
//        }
        if($user){
            $favorite = Favorite::where('user_id' , $user->id)->where('product_id' , $data->id)->where('type','product')->first();
            if($favorite){
                $data->favorite = true;
            }else{
                $data->favorite = false;
            }
        }else{
            $data->favorite = false;
        }



        $related = Product::where('category_id' ,  $data->category_id)
            ->where('id' , '!=' ,  $data->id)
            ->select('id','title_'.$lang.' as title','final_price','price_before_offer','offer','offer_percentage','category_id')
            ->limit(4)
            ->get();
        for($i = 0 ; $i < count($related); $i++){
            if($user){
                $favorite = Favorite::where('user_id' , $user->id)->where('product_id' , $related[$i]['id'])->where('type','product')->first();
                if($favorite){
                    $related[$i]['favorite'] = true;
                }else{
                    $related[$i]['favorite'] = false;
                }
            }else{
                $related[$i]['favorite'] = false;
            }
        }
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' ,array( 'product'=>$data,'related'=>$related), $request->lang );
        return response()->json($response , 200);
    }
}
