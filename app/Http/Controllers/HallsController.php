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

class HallsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['excute_store_reservation','store_reservation','all_halls','details','rates','store_rate']]);
    }
    public function all_halls(Request $request,$type) {
        $user = auth()->user();
        $halls = Hole_time_work::where('type',$type)->get();
        foreach ($halls as $key => $hall){
            $selected_hall = Hole::findOrFail($hall->hole_id);
            if($selected_hall->deleted == '0' && $selected_hall->status == 'active'){
                $data[$key]['id'] = $selected_hall->id;
                $data[$key]['cover'] = $selected_hall->cover;
                $data[$key]['logo'] = $selected_hall->logo;
                $data[$key]['name'] = $selected_hall->name;
                $data[$key]['rate'] = $selected_hall->rate;

                if($user == null){
                    $data[$key]['favorite'] = false ;
                }else{
                    $fav = Favorite::where('user_id', $user->id)->where('product_id', $selected_hall->id)->where('type','hall')->first();
                    if($fav == null){
                        $data[$key]['favorite'] = false ;
                    }else{
                        $data[$key]['favorite'] = true ;
                    }
                }
            }
        }
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $data, $request->lang );
        return response()->json($response , 200);
    }
    public function rates(Request $request) {
        $lang = $request->lang ;
        $rates = Rate::select('text','rate','user_id as user_name','created_at')->where('type',$request->type)
            ->where('order_id',$request->id)
            ->where('admin_approval',1)
            ->get()
            ->map(function($rate) use ($lang){
                $user = User::where('id',$rate->user_name)->first();
                $rate->user = $user->name;
//                $rate->created_at = APIHelpers::get_month_year($rate->created_at, $lang);
                return $rate;
            });
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $rates, $request->lang );
        return response()->json($response , 200);
    }

    public function reservation_types(Request $request) {
        $lang = $request->lang ;
        $data = null;
        if($request->type == 'types'){
            if($lang == 'ar'){
                $data = Reservation_type::select('id','title_ar as title')->where('deleted','0')->get();
            }else{
                $data = Reservation_type::select('id','title_en as title')->where('deleted','0')->get();
            }
        }else if($request->type == 'goals'){
            if($lang == 'ar'){
                $data = Reservation_goal::select('id','title_ar as title')->where('deleted','0')->get();
            }else{
                $data = Reservation_goal::select('id','title_en as title')->where('deleted','0')->get();
            }
        }
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $data, $request->lang );
        return response()->json($response , 200);
    }
    public function store_rate(Request $request,$type){
        $validator = Validator::make($request->all(), [
            'text' => 'required',
            'rate' => 'required|numeric|min:1|max:5',
            'target_id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,  $validator->errors()->first(), $validator->errors()->first() , null, $request->lang );
            return response()->json($response , 406);
        }
        $user = auth()->user();

        if($user == null){
            $response = APIHelpers::createApiResponse(true , 406 ,  'you should login first', 'يجب تسجيل الدخول اولا' , null, $request->lang );
            return response()->json($response , 406);
        }
        $data['user_id'] = $user->id ;
        $data['text'] = $request->text ;
        $data['rate'] = $request->rate ;
        $data['order_id'] = $request->target_id ;
        $data['admin_approval'] = 2 ;
        $data['type'] = $type ;
        $rating = Rate::create($data);
//        if($rating != null){
//            $total_rates = Rate::where('order_id',$request->target_id)->where( 'admin_approval' , 1 )->where( 'type' , $type )->get();
//            $sum_rates = $total_rates->sum('rate');
//            $count_rates = count($total_rates);
//            $new_rate = $sum_rates / $count_rates ;
//            if($type == 'hall'){
//                $hall = Hole::findOrFail($request->target_id);
//                $hall->rate = $new_rate;
//                $hall->save();
//            }else if($type == 'coach'){
//                $hall = Coach::findOrFail($request->target_id);
//                $hall->rate = $new_rate;
//                $hall->save();
//            }
//        }
        $response = APIHelpers::createApiResponse(false , 200 ,  'rate added successfully', 'تم اضافة التقييم بنجاح' , null, $request->lang );
        return response()->json($response , 200);
    }

    public function store_reservation(Request $request) {
        $booking = Hole_booking::where('id',$request->booking_id)->first();
        if($booking == null){
            $response = APIHelpers::createApiResponse(true , 406 , 'يجب اختيار اشتراك صحيح اولا' ,' You must choose a valid subscription first', (object)[] , $request->lang);
            return response()->json($response , 406);
        }
        $price = null ;
        if($booking->is_discount == '1'){
            $price = $booking->discount_price ;
        }else{
            $price = $booking->price ;
        }
        $user = auth()->user();
        $root_url = $request->root();
        $path='https://apitest.myfatoorah.com/v2/SendPayment';
        $token="bearer rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL";
        $headers = array(
            'Authorization:' .$token,
            'Content-Type:application/json'
        );
        $call_back_url = $root_url."/api/reservation/excute_pay?user_id=".$user->id."&booking_id=".$request->booking_id.
            "&price=".$price."&name=".$request->name."&age=".$request->age."&length=".$request->length."&weight=".
            $request->weight."&type_id=".$request->type_id."&goal_id=".$request->goal_id."&other=".$request->other;
        $error_url = $root_url."/api/pay/error";
        $fields =array(
            "CustomerName" => $user->name,
            "NotificationOption" => "LNK",
            "InvoiceValue" => $price,
            "CallBackUrl" => $call_back_url,
            "ErrorUrl" => $error_url,
            "Language" => "AR",
            "CustomerEmail" => $user->email
        );

        $payload =json_encode($fields);
        $curl_session =curl_init();
        curl_setopt($curl_session,CURLOPT_URL, $path);
        curl_setopt($curl_session,CURLOPT_POST, true);
        curl_setopt($curl_session,CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl_session,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl_session,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_session,CURLOPT_IPRESOLVE, CURLOPT_IPRESOLVE);
        curl_setopt($curl_session,CURLOPT_POSTFIELDS, $payload);
        $result=curl_exec($curl_session);
        curl_close($curl_session);
        $result = json_decode($result);

        $data['url'] = $result->Data->InvoiceURL;
        $response = APIHelpers::createApiResponse(false , 200 ,  '' , '' , $data , $request->lang );
        return response()->json($response , 200);
    }
    public function excute_store_reservation(Request $request){
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,  'بعض الحقول مفقودة', 'بعض الحقول مفقودة' , null, $request->lang );
            return response()->json($response , 406);
        }
        $user = auth()->user();
        $data['user_id'] = $request->user_id ;
        $data['name'] = $request->name ;
        $data['age'] = $request->age ;
        $data['length'] = $request->length ;
        $data['weight'] = $request->weight ;
        $data['type_id'] = $request->type_id ;
        $data['goal_id'] = $request->goal_id ;
        $data['other'] = $request->other ;
        $data['booking_id'] = $request->booking_id ;
        $data['price'] = $request->price ;

        //get booking data to get expire date
        $booking = Hole_booking::find($request->booking_id);
        $mytime = Carbon::now();
        $today =  Carbon::parse($mytime->toDateTimeString())->format('Y-m-d H:i');
        $final_date = Carbon::createFromFormat('Y-m-d H:i', $today);
        $final_expire_date = $final_date->addMonths($booking->months_num);
        $data['expire_date'] = $final_expire_date ;
        $reserve = Reservation::create($data);
        if($reserve != null){
            $income_Data['price'] = $request->price ;
            $income_Data['type'] = 'hall' ;
            $income_Data['user_id'] = $request->user_id ;
            $income_Data['reservation_id'] = $reserve->id ;
            $income_Data['booking_id'] = $request->booking_id ;
            Income::createe($income_Data);
        }

        $response = APIHelpers::createApiResponse(false , 200 ,  'تم الحجز فالاشتراك بنجاح', 'Reservation saves successfully' , null, $request->lang );
        return response()->json($response , 200);
    }
    public function details(Request $request,$id) {
        $lang = $request->lang ;
        $user = auth()->user();
        Session::put('api_lang',$lang);
        $hall = Hole::select('id','cover','logo','name','about_hole','rate')->find($id);
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
