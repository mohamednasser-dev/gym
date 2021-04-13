<?php

namespace App\Http\Controllers;

use App\Coach;
use App\Favorite;
use App\Hole;
use App\Hole_booking;
use App\Hole_branch;
use App\Hole_time_work;
use App\Product;
use App\Product_feature;
use App\Product_view;
use App\ProductImage;
use App\Rate;
use App\Reservation;
use App\Reservation_goal;
use App\Reservation_type;
use App\Setting;
use App\User;
use App\User_caoch_ask;
use App\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use JD\Cloudder\Facades\Cloudder;

class CoachesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['all_coaches','details','login','update_coach_data','ny_data']]);
    }
    public function all_coaches(Request $request) {
        $user = auth()->user();
        $coaches = Coach::select('id','name','rate','image')
                        ->where('deleted','0')
                        ->where('status','active')
                        ->where('is_confirm','accepted')
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
                $data['free_ask'] = false ;
            }else{
                $fav = Favorite::where('user_id', $user->id)->where('product_id', $id)->where('type','coach')->first();
                if($fav == null){
                    $data['favorite'] = false ;
                }else{
                    $data['favorite'] = true ;
                }
                //if user have free ask
                $free_ask = User_caoch_ask::where('user_id', $user->id)->where('coach_id', $id)->first();
                if($free_ask->ask_num_free > 0){
                    $data['free_ask'] = true ;
                }else{
                    $data['free_ask'] = false ;
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
//    coach-panel ------------------------------------------------------------------
    public function login(Request $request){
        $credentials = request(['phone', 'password']);
        $input = $request->all();
        $validate = Validator::make($input , [
            'phone'=>'required',
            'password'=>'required',
        ]);
        if (!is_array($validate))
        {
           if( Auth::guard('coach')->attempt($credentials)){
               $user = auth()->guard('coach')->user();
               $user->fcm_token = $request->fcm_token;
               $user->save();

               if($user->is_confirm == 'new'){
                   $response = APIHelpers::createApiResponse(true , 406 , 'please wait confirmation your account' , 'يرجى انتظار موافقه الادارة' , null , $request->lang);
                   return response()->json($response , 406);
               }
               if($user->is_confirm == 'rejected'){
                   $response = APIHelpers::createApiResponse(true , 406 , 'your account rejected' , 'قد تم رفض حسابك' , null , $request->lang);
                   return response()->json($response , 406);
               }
               $token = auth()->login($user);
               $user->token = $this->respondWithToken($token);
               $response = APIHelpers::createApiResponse(false , 200 , 'login successfully' , 'تم تسجيل الدخول' , $user , $request->lang);
               return response()->json($response , 200);
            } else {
                   $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة' , null , $request->lang);
                   return response()->json($response , 406);
            }
        }
    }
    public function register(Request $request){
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            "age" => "required",
            "exp" => "required",
            "about_coach" => "",
            "gender" => "required",
            "image" => "required",
            "email" => 'required|email|unique:coaches,email',
            "phone" => 'required|unique:coaches,phone',
            "password" => 'required',
            "fcm_token" => 'required',
            "type" => "required", // 1 -> iphone , 2 -> android
            "unique_id" => "required",
        ]);
        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,$validator->errors()->first()  , $validator->errors()->first()  , null, $request->lang);
            return response()->json($response , 406);
        }
        if($request->password != null){
            $input['password'] = Hash::make($request->password);
            $input['is_confirm'] = 'new';
            $input['sort'] = 10000;
            if($request->image != null){
                $image = $request->image;
                Cloudder::upload("data:image/jpeg;base64,".$image, null);
                $imagereturned = Cloudder::getResult();
                $image_id = $imagereturned['public_id'];
                $image_format = $imagereturned['format'];
                $image_new_name = $image_id.'.'.$image_format;
                $input['image'] = $image_new_name;
            }
            $coach = Coach::create($input);

            // add free coach ask to every user
            $users = User::all();
            foreach ($users as $row){
                $data['user_id'] = $row->id;
                $data['caoch_id'] = $coach->id;
                User_caoch_ask::create($data);
            }
        }

        $response = APIHelpers::createApiResponse(false , 200  , 'Registered success wait admin to accept your account', 'تم انشاء الحساب ويرجى الانتظار لموافقه الادارة' , null, $request->lang );
        return response()->json($response , 200);
    }
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 432000
        ];
    }

    //update my profile ------------------------
    public function ny_data(Request $request){
        $user = auth()->guard('coach')->user();
        $lang = $request->lang;
        if($user == null){
            $response = APIHelpers::createApiResponse(true , 406 , 'you should login' ,'يجب تسجيل الدخول', null , $request->lang);
            return response()->json($response , 406);
        }else {
            $data = Coach::select('id','name','image','story','about_coach','time_from','time_to')->where('id',$user->id)->first();
            $response = APIHelpers::createApiResponse(false , 200 ,  '', '' ,$data, $request->lang );
            return response()->json($response , 200);
        }
    }
    public function update_coach_data(Request $request) {
        $lang = $request->lang ;
        $user = auth()->guard('coach')->user();
        if($user == null){
            $response = APIHelpers::createApiResponse(true , 406 , 'you should login' ,'يجب تسجيل الدخول', null , $request->lang);
            return response()->json($response , 406);
        }
        $input = $request->all();
        $validator = Validator::make($input , [
            'name' => 'required',
            'about_coach' => 'required',
            'image' => '',
            'time_from' => 'required',
            'time_to' => 'required',
            'story' => ''
        ]);
        if($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , $validator->messages()->first() ,$validator->messages()->first(), null , $request->lang);
            return response()->json($response , 406);
        }else {
            if($request->image != null){
                $image = $request->image;
                Cloudder::upload("data:image/jpeg;base64,".$image, null);
                $imagereturned = Cloudder::getResult();
                $image_id = $imagereturned['public_id'];
                $image_format = $imagereturned['format'];
                $image_new_name = $image_id.'.'.$image_format;
                $input['image'] = $image_new_name;
            }else{
                unset($input['image']);
            }
            if($request->story != null){
                $uniqueid = uniqid();
                $original_name = $request->file('story')->getClientOriginalName();
                $size = $request->file('story')->getSize();
                $file = $request->file('story');
                $extension = $request->file('story')->getClientOriginalExtension();
                $filename = Carbon::now()->format('Ymd') . '_' . $uniqueid . '.' . $extension;
                $audiopath = url('/storage/uploads/stories/' . $filename);
                $path = $file->storeAs('public/uploads/stories/', $filename);
                $file->move(public_path('uploads/stories'), $filename);
                $all_audios = $audiopath;
                $input['story'] = $filename;
            }
            $coach = Coach::where('id',$user->id)->update($input);
            $response = APIHelpers::createApiResponse(false, 200, 'updated', 'تم التعديل بنجاح', (object)[] , $request->lang);
            return response()->json($response, 200);
        }
    }

}
