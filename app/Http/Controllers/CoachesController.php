<?php

namespace App\Http\Controllers;

use App\Coach;
use App\Favorite;
use App\Hole;
use App\Hole_booking;
use App\Hole_branch;
use App\Hole_time_work;
use App\Rate;
use App\Reservation;
use App\Reservation_goal;
use App\Reservation_type;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;
use Illuminate\Support\Facades\Validator;

class CoachesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['all_coaches','details']]);
    }
    public function all_coaches(Request $request) {
        $user = auth()->user();
        $coaches = Coach::select('id','name','rate','image')->where('deleted','0')
            ->where('status','active')
            ->where('verified','1')
            ->get()
            ->map(function($coaches) use($user){
                if($user == null){
                    $coaches->favorite = false ;
                }else{
                    $fav = Favorite::where('user_id', $user->id)->where('product_id', $coaches->id)->where('type','coach')->first();
                    if($fav == null){
                        $coaches->favorite = false ;
                    }else{
                        $coaches->favorite = true ;
                    }
                }
                return $coaches;
            });


        //expict favorite and plans num ...
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $coaches, $request->lang );
        return response()->json($response , 200);
    }

    public function details(Request $request,$id) {
        $lang = $request->lang ;
        $user = auth()->user();
        $coach = Coach::select('id','image','name','about_coach','rate')->find($id);
        if($coach != null){
            if($user == null){
                $data['favorite'] = false ;
            }else{
                $fav = Favorite::where('user_id', $user->id)->where('product_id', $id)->where('type','coach')->first();
                if($fav == null){
                    $data['favorite'] = false ;
                }else{
                    $data['favorite'] = true ;
                }
            }
            $data['basic'] = $coach;

            $rates_one = Rate::where('type','coach')->where('admin_approval',1)->where('order_id',$id)->where('rate',1)->get()->count();
            $rates_tow = Rate::where('type','coach')->where('admin_approval',1)->where('order_id',$id)->where('rate',2)->get()->count();
            $rates_three = Rate::where('type','coach')->where('admin_approval',1)->where('order_id',$id)->where('rate',3)->get()->count();
            $rates_four = Rate::where('type','coach')->where('admin_approval',1)->where('order_id',$id)->where('rate',4)->get()->count();
            $rates_five = Rate::where('type','coach')->where('admin_approval',1)->where('order_id',$id)->where('rate',5)->get()->count();
            $data['stars_count']['one'] = $rates_one;
            $data['stars_count']['tow'] = $rates_tow;
            $data['stars_count']['three'] = $rates_three;
            $data['stars_count']['four'] = $rates_four;
            $data['stars_count']['five'] = $rates_five;

            $data['all_rates'] = Rate::select('text','rate','user_id as user_name','created_at')->where('type','coach')
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
        }
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $data, $request->lang );
        return response()->json($response , 200);
    }

}
