<?php

namespace App\Http\Controllers;

use App\Coach;
use App\Favorite;
use App\Hole;
use App\Hole_booking;
use App\Hole_branch;
use App\Hole_time_work;
use App\Income;
use App\Rate;
use App\Reservation;
use App\Reservation_goal;
use App\Reservation_type;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SubscriptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['subscriptions']]);
    }
    public function subscriptions( Request $request , $type) {
        $lang = $request->lang ;
        $user_id = auth()->user()->id;
        if($type == 'hall'){
        $data = Reservation::select('id','booking_id','expire_date','price','user_id','status')
            ->where('type',$type)
            ->where('user_id',$user_id)
            ->get()
            ->map(function($reserv) use ($lang){
                $reserv->hall_name = $reserv->Booking->Hall->name;
                $reserv->hall_logo = $reserv->Booking->Hall->logo;
                if($lang == 'ar'){
                    $reserv->reserve_name = $reserv->Booking->name_ar;
                }else{
                    $reserv->reserve_name = $reserv->Booking->name_en;
                }
                return $reserv;
            });

            foreach ($data as $key => $row){
                $subscriptions[$key]['id'] = $row->id;
                $subscriptions[$key]['booking_id'] = $row->booking_id;
                $subscriptions[$key]['hall_id'] = $row->Booking->hole_id;
                $subscriptions[$key]['logo'] = $row->hall_logo;
                $subscriptions[$key]['hall_name'] = $row->hall_name;
                $subscriptions[$key]['reserve_name'] = $row->reserve_name;
                $subscriptions[$key]['price'] = $row->price;
                $subscriptions[$key]['expire_date'] = $row->expire_date;
                $subscriptions[$key]['status'] = $row->status;
            }
        }else if($type == 'coach'){
            $data = Reservation::select('id','booking_id','expire_date','price','user_id','status')
                ->where('type',$type)
                ->where('user_id',$user_id)
                ->get()
                ->map(function($reserv) use ($lang){
                    $reserv->coach_name = $reserv->Booking_coach->Coach->name;
                    $reserv->coach_logo = $reserv->Booking_coach->Coach->image;
                    if($lang == 'ar'){
                        $reserv->reserve_name = $reserv->Booking_coach->name_ar;
                    }else{
                        $reserv->reserve_name = $reserv->Booking_coach->name_en;
                    }
                    return $reserv;
                });
            foreach ($data as $key => $row){
                $subscriptions[$key]['id'] = $row->id;
                $subscriptions[$key]['booking_id'] = $row->booking_id;
                $subscriptions[$key]['coach_id'] = $row->Booking_coach->coach_id;
                $subscriptions[$key]['logo'] = $row->coach_logo;
                $subscriptions[$key]['coach_name'] = $row->coach_name;
                $subscriptions[$key]['reserve_name'] = $row->reserve_name;
                $subscriptions[$key]['price'] = $row->price;
                $subscriptions[$key]['expire_date'] = $row->expire_date;
                $subscriptions[$key]['status'] = $row->status;
            }
        }
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $subscriptions, $request->lang );
        return response()->json($response , 200);
    }

    public function payments( Request $request) {
        $lang = $request->lang ;
        $user_id = auth()->user()->id;
        $my_balance = User::where('id',$user_id)->select('id' , 'my_wallet as my_balance')->first();
        $data = Reservation::select('id','booking_id','expire_date','price','user_id','created_at','type')
            ->where('user_id',$user_id)
            ->orderBy('created_at','desc')
            ->get()
            ->map(function($reserv) use ($lang){
                if($reserv->type == 'hall'){
                    $reserv->name = $reserv->Booking->Hall->name;
                    if($lang == 'ar'){
                        $reserv->reserve_name = $reserv->Booking->name_ar;
                    }else{
                        $reserv->reserve_name = $reserv->Booking->name_en;
                    }
                }else{
                    $reserv->name = $reserv->Booking_coach->Coach->name;
                    if($lang == 'ar'){
                        $reserv->reserve_name = $reserv->Booking_coach->name_ar;
                    }else{
                        $reserv->reserve_name = $reserv->Booking_coach->name_en;
                    }
                }
                return $reserv;
            });

        foreach ($data as $key => $row){
            $subscriptions[$key]['id'] = $row->id;
            $subscriptions[$key]['name'] = $row->name;
            $subscriptions[$key]['price'] = $row->price;
            $subscriptions[$key]['created_at'] = $row->created_at;
        }

        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , array('my_balance'=> $my_balance , 'history' => $subscriptions) , $request->lang );
        return response()->json($response , 200);
    }
}
