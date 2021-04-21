<?php

namespace App\Http\Controllers;

use App\Coach_booking;
use App\Hole_media;
use App\Reservation_option;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;
use App\Reservation_goal;
use App\Reservation_type;
use App\Hole_time_work;
use App\Hole_booking;
use App\Hole_branch;
use App\Reservation;
use Carbon\Carbon;
use App\Favorite;
use App\Income;
use App\Hole;
use App\Rate;
use App\User;

class HallsController extends Controller
{
    public $personal_data = [];
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['excute_store_reservation','store_reservation','all_halls','details','rates','store_rate']]);
        //        --------------------------------------------- begin scheduled functions --------------------------------------------------------
        $expired = Reservation::where('status','start')->whereDate('expire_date', '<', Carbon::now())->get();
        foreach ($expired as $row){
            $product = Reservation::find($row->id);
            $product->status = 'ended';
            $product->save();
        }
        //        --------------------------------------------- end scheduled functions --------------------------------------------------------
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
        Session::put('api_lang',$lang);
        $data = null;
        if($lang == 'ar'){
            $data = Reservation_type::with('Goals')
                ->select('id','title_ar as title')
                ->where('deleted','0')
                ->get()
                ->map(function($types){
                    if(count($types->Goals) != 0){
                        $types->type = 'select';
                    }else{
                        $types->type = 'input';
                    }
                    return $types;
                });
        }else{
            $data = Reservation_type::with('Goals')
                ->select('id','title_en as title')
                ->where('deleted','0')
                ->get()
                ->map(function($types){
                    if(count($types->Goals) != 0){
                        $types->type = 'select';
                    }else{
                        $types->type = 'input';
                    }
                    return $types;
                });
        }
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $data, $request->lang );
        return response()->json($response , 200);
    }
    public function store_rate(Request $request,$type){
        $validator = Validator::make($request->all(), [
            'text' => 'required',
            'rate' => 'required|numeric|min:1|max:5'
        ]);
        if($type == 'hall'){
            $validator = Validator::make($request->all(), [
                'target_id' => 'required|exists:holes,id'
            ]);
        }else if($type == 'coach'){
            $validator = Validator::make($request->all(), [
                'target_id' => 'required|exists:coaches,id'
            ]);
        }
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

        $response = APIHelpers::createApiResponse(false , 200 ,  'rate added successfully', 'تم اضافة التقييم بنجاح' , null, $request->lang );
        return response()->json($response , 200);
    }

    public function store_reservation(Request $request,$type) {
        $this->personal_data = $request->personal_data ;
        if($type == 'hall'){
            $booking = Hole_booking::where('id',$request->booking_id)->first();
        }else{
            $booking = Coach_booking::where('id',$request->booking_id)->first();
        }

        if($booking == null){
            $response = APIHelpers::createApiResponse(true , 406 , 'يجب اختيار اشتراك صحيح اولا' ,' You must choose a valid subscription first', (object)[] , $request->lang);
            return response()->json($response , 406);
        }
        $price = null ;
        if($booking->is_discount == 1){
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

        //for loop
        $p_d_types = null;
        $p_d_goals = null;
        foreach($request->personal_data_types as $key => $row){

            $p_d_types = $p_d_types.'&personal_data_types='.$row;
        }
        foreach($request->personal_data_goals as $key => $row){

            $p_d_goals = $p_d_goals.'&personal_data_goals='.$row;
        }
        //end for loop
        $call_back_url = $root_url."/api/reservation/excute_pay?user_id=".$user->id."&booking_id=".$request->booking_id.
            "&price=".$price.
            "&type=".$type.
            $p_d_types.
            $p_d_goals;

        dd($call_back_url);

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
        if($request->type == 'hall') {
            $validator = Validator::make($request->all(), [
                'booking_id' => 'required|exists:hole_bookings,id',
            ]);
        }else if($request->type == 'coach'){
            $validator = Validator::make($request->all(), [
                'booking_id' => 'required|exists:coach_bookings,id',
            ]);
        }
        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,  $validator->errors()->first(), $validator->errors()->first() , null, $request->lang );
            return response()->json($response , 406);
        }
        $user = auth()->user();
        $data['user_id'] = $request->user_id ;
        $data['booking_id'] = $request->booking_id ;
        $data['price'] = $request->price ;

        //get booking data to get expire date
        if($request->type == 'hall'){
            $booking = Hole_booking::find($request->booking_id);
        }else{
            $booking = Coach_booking::find($request->booking_id);
        }

        $mytime = Carbon::now();
        $today =  Carbon::parse($mytime->toDateTimeString())->format('Y-m-d H:i');
        $final_date = Carbon::createFromFormat('Y-m-d H:i', $today);
        $final_expire_date = $final_date->addMonths($booking->months_num);
        $data['expire_date'] = $final_expire_date ;
        $data['type'] = $request->type;
        $reserve = Reservation::create($data);
        if($reserve != null){
            foreach($request->personal_data_types as $key => $row){
                $otion_data['reservation_id'] = $reserve->id;
                $otion_data['type_id'] = $row;
                $otion_data['goal_id'] = $request->personal_data_goals[$key];
                Reservation_option::create($otion_data);
            }
            $income_Data['price'] = $request->price ;
            $income_Data['type'] = $request->type ;
            $income_Data['user_id'] = $request->user_id ;
            $income_Data['reservation_id'] = $reserve->id ;
            $income_Data['booking_id'] = $request->booking_id ;
            Income::create($income_Data);
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
