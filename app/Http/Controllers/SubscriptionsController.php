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
        if($type == 'halls'){
        $data = Reservation::select('id','booking_id','expire_date','price','user_id')
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
        }
;
        foreach ($data as $key => $row){
            $subscriptions[$key]['id'] = $row->id;
            $subscriptions[$key]['booking_id'] = $row->booking_id;
            $subscriptions[$key]['hall_id'] = $row->Booking->hole_id;
            $subscriptions[$key]['logo'] = $row->hall_logo;
            $subscriptions[$key]['hall_name'] = $row->hall_name;
            $subscriptions[$key]['reserve_name'] = $row->reserve_name;
            $subscriptions[$key]['price'] = $row->price;
            $subscriptions[$key]['expire_date'] = $row->expire_date;
        }
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $subscriptions, $request->lang );
        return response()->json($response , 200);
    }
}
