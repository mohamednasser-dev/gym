<?php

namespace App\Http\Controllers;

use App\Coach;
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

        $coaches = Coach::select('id','name','rate','image')->where('deleted','0')->where('status','active')->where('verified','1')->get();
        //expict favorite and plans num ...
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $coaches, $request->lang );
        return response()->json($response , 200);
    }
    public function details(Request $request,$id) {
        $lang = $request->lang ;
        $hall = Coach::select('id','cover','logo','name','about_hole','rate')->find($id);
        if($hall != null){
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
            if($lang == 'ar') {
                $data['branches'] = Hole_branch::select('id', 'title_ar as title', 'latitude', 'longitude')->where('hole_id', $id)->get();
            }else{
                $data['branches'] = Hole_branch::select('id', 'title_en as title', 'latitude', 'longitude')->where('hole_id', $id)->get();
            }

            $rates_one = Rate::where('type','hall')->where('order_id',$id)->where('rate',1)->get()->count();
            $rates_tow = Rate::where('type','hall')->where('order_id',$id)->where('rate',2)->get()->count();
            $rates_three = Rate::where('type','hall')->where('order_id',$id)->where('rate',3)->get()->count();
            $rates_four = Rate::where('type','hall')->where('order_id',$id)->where('rate',4)->get()->count();
            $rates_five = Rate::where('type','hall')->where('order_id',$id)->where('rate',5)->get()->count();
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
            $data['reservations'] = Hole_booking::with('Details')->select('id','name','title as description','price','is_discount','discount','discount_price','common')->where('hole_id',$id)->where('deleted','0')->get();
        }
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $data, $request->lang );
        return response()->json($response , 200);
    }

}
