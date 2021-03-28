<?php

namespace App\Http\Controllers;

use App\Hole;
use App\Hole_branch;
use App\Hole_time_work;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;

use Carbon\Carbon;
class HallsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['all_halls','details']]);
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
