<?php

namespace App\Http\Controllers;

use App\Coach;
use App\ContactUs;
use App\Hole;
use App\Hole_branch;
use App\Hole_time_work;
use App\Rate;
use App\User;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class HallsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['all_halls','details','rates','store_rate']]);
    }
    public function all_halls(Request $request,$type) {
        $halls = Hole_time_work::where('type',$type)->get();
        foreach ($halls as $key => $hall){
            $selected_hall = Hole::findOrFail($hall->hole_id);
            if($selected_hall->deleted == '0' && $selected_hall->status == 'active'){
                $data[$key]['id'] = $selected_hall->id;
                $data[$key]['cover'] = $selected_hall->cover;
                $data[$key]['logo'] = $selected_hall->logo;
                $data[$key]['name'] = $selected_hall->name;
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
    public function store_rate(Request $request,$type){
        $validator = Validator::make($request->all(), [
            'text' => 'required',
            'rate' => 'required',
            'target_id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,  'بعض الحقول مفقودة', 'بعض الحقول مفقودة' , null, $request->lang );
            return response()->json($response , 406);
        }
        $user = auth()->user();
        $data['user_id'] = $user->id ;
        $data['text'] = $request->text ;
        $data['rate'] = $request->rate ;
        $data['order_id'] = $request->target_id ;
        $data['admin_approval'] = 1 ;
        $data['type'] = $type ;
        $rating = Rate::create($data);
        if($rating != null){
            $total_rates = Rate::where('order_id',$request->target_id)->where( 'admin_approval' , 1 )->where( 'type' , $type )->get();
            $total_rates = Rate::where('order_id',$request->target_id)->where( 'admin_approval' , 1 )->where( 'type' , $type )->get();
            $sum_rates = $total_rates->sum('rate');
            $count_rates = count($total_rates);
            $new_rate = $sum_rates / $count_rates ;
            if($type == 'hall'){
                $hall = Hole::findOrFail($request->target_id);
                $hall->rate = $new_rate;
                $hall->save();
            }else if($type == 'coach'){
                $hall = Coach::findOrFail($request->target_id);
                $hall->rate = $new_rate;
                $hall->save();
            }
        }
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , null, $request->lang );
        return response()->json($response , 200);
    }
    public function details(Request $request,$id) {
        $lang = $request->lang ;
        $hall = Hole::select('id','cover','logo','name','about_hole')->find($id);
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
                                                    return $time;
                                                });
            if($lang == 'ar') {
                $data['branches'] = Hole_branch::select('id', 'title_ar as title', 'latitude', 'longitude')->where('hole_id', $id)->get();
            }else{
                $data['branches'] = Hole_branch::select('id', 'title_en as title', 'latitude', 'longitude')->where('hole_id', $id)->get();
            }
        }
        $response = APIHelpers::createApiResponse(false , 200 ,  '', '' , $data, $request->lang );
        return response()->json($response , 200);
    }
}
