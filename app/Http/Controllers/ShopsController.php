<?php

namespace App\Http\Controllers;

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
use App\Rate;
use App\User;

class ShopsController extends Controller
{
    public $personal_data = [];
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['all_shops','details']]);

    }
    public function all_shops(Request $request) {
        $lang = $request->lang ;
        $user = auth()->user();
        if($lang == 'ar'){
            $shops = Shop::select('id','name_ar as title','logo','cover')
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
        }else{
            $shops = Shop::select('id','name_en as title','logo','cover')
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
        }

        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $shops, $request->lang );
        return response()->json($response , 200);
    }

    public function details(Request $request,$id) {
        $lang = $request->lang ;
        $user = auth()->user();
        Session::put('api_lang',$lang);
        if($lang == 'ar' ){
            $hall = Hole::select('id','cover','logo','name','about_hole','rate')->find($id);
        }else{
            $hall = Hole::select('id','cover','logo','name_en as name','about_hole_en as about_hole','rate')->find($id);
        }
        if($hall != null){
            if($user == null){
                $data['favorite'] = false ;
            }else{
                $fav = Favorite::where('user_id', $user->id)->where('product_id', $id)->where('type','hall')->first();
                if($fav == null){
                    $data['favorite'] = false ;
                }else{
                    $data['favorite'] = true ;
                }
            }
            $data['basic'] = $hall;
            $data['work_times'] = Hole_time_work::select('id','time_from','time_to','type')
                                                ->where('hole_id',$id)
                                                ->get()
                                                ->map(function($time) use ($lang){
                                                    if($lang == 'ar'){
                                                        if($time->type == 'male'){
                                                            $time->type = 'الرجالية';
                                                        }else if($time->type == 'female'){
                                                            $time->type = 'النسائية';
                                                        }else if($time->type == 'mix'){
                                                            $time->type = 'المختلط';
                                                        }
                                                    }
//                                                    $time->rate =
                                                    return $time;
                                                });

            $data['media'] = Hole_media::select('id','image','type')
                ->where('hole_id',$id)
                ->get()
                ->map(function($media){
                    if($media->type == 'video'){
                        $media->image = env('APP_URL') . '/public/uploads/hall_media'. $media->image ;
                    }
                    return $media;

                });
            if($lang == 'ar') {
                $data['branches'] = Hole_branch::select('id', 'title_ar as title', 'latitude', 'longitude')->where('hole_id', $id)->get();
            }else{
                $data['branches'] = Hole_branch::select('id', 'title_en as title', 'latitude', 'longitude')->where('hole_id', $id)->get();
            }

            $rates_one = Rate::where('type','hall')->where('admin_approval',1)->where('order_id',$id)->where('rate',1)->get()->count();
            $rates_tow = Rate::where('type','hall')->where('admin_approval',1)->where('order_id',$id)->where('rate',2)->get()->count();
            $rates_three = Rate::where('type','hall')->where('admin_approval',1)->where('order_id',$id)->where('rate',3)->get()->count();
            $rates_four = Rate::where('type','hall')->where('admin_approval',1)->where('order_id',$id)->where('rate',4)->get()->count();
            $rates_five = Rate::where('type','hall')->where('admin_approval',1)->where('order_id',$id)->where('rate',5)->get()->count();

            $data['stars_count']['one'] = $rates_one;
            $data['stars_count']['tow'] = $rates_tow;
            $data['stars_count']['three'] = $rates_three;
            $data['stars_count']['four'] = $rates_four;
            $data['stars_count']['five'] = $rates_five;

            $data['all_rates'] = Rate::select('text','rate','user_id as user_name','created_at')->where('type','hall')
                                    ->where('order_id',$id)
                                    ->where('admin_approval',1)
                                    ->get()
                                    ->map(function($rate) use ($lang){
                                        $user = User::where('id',$rate->user_name)->first();
                                        $rate->user = $user->name;
                    //                $rate->created_at = APIHelpers::get_month_year($rate->created_at, $lang);
                                        return $rate;
                                    });
            $data['rates_count'] = count($data['all_rates']);
            if($lang == 'ar'){
                $data['reservations'] = Hole_booking::with('Details')
                                                    ->select('id','name_ar as name','title_ar as description','price','is_discount','discount','discount_price','common')
                                                    ->where('hole_id',$id)
                                                    ->where('deleted','0')
                                                    ->orderBy('common','desc')
                                                    ->get();
            }else{
                $data['reservations'] = Hole_booking::with('Details')
                                                    ->select('id','name_en as name','title_en as description','price','is_discount','discount','discount_price','common')
                                                    ->where('hole_id',$id)
                                                    ->where('deleted','0')
                                                    ->orderBy('common','desc')
                                                    ->get();
            }
        }
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $data, $request->lang );
        return response()->json($response , 200);
    }
}
