<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;
use App\Favorite;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => []]);
    }

    public function addtofavorites(Request $request){
        $user = auth()->user();
        if($user->active == 0){
            $response = APIHelpers::createApiResponse(true , 406 ,  'your account blocked', 'تم حظر حسابك' , null, $request->lang );
            return response()->json($response , 406);
        }
        $validator = Validator::make($request->all() , [
            'product_id' => 'required',
            'type' => 'required|in:hall,coach,shop,product',
        ]);
        if($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,   $validator->errors()->first(), $validator->errors()->first() , null, $request->lang );
            return response()->json($response , 406);
        }
        $favorite = Favorite::where('product_id' , $request->product_id)->where('type',$request->type)->where('user_id' , $user->id)->first();
        if($favorite){
            $response = APIHelpers::createApiResponse(true , 406 ,  'this favorite added before', 'تم إضافه هذا المفضل  من قبل' , null, $request->lang );
            return response()->json($response , 406);
        }else{
            $favorite = new Favorite();
            $favorite->user_id = $user->id;
            $favorite->product_id = $request->product_id;
            $favorite->type = $request->type;
            $favorite->save();
            $response = APIHelpers::createApiResponse(false , 200 ,  'success added', 'تم الاضافة بنجاح' , $favorite, $request->lang);
            return response()->json($response , 200);
        }
    }

    public function removefromfavorites(Request $request){
        $user = auth()->user();
        if($user->active == 0){
            $response = APIHelpers::createApiResponse(true , 406 ,  'your account blocked', 'تم حظر حسابك' , null, $request->lang );
            return response()->json($response , 406);
        }
        $validator = Validator::make($request->all() , [
            'fav_id' => 'required',
            'type' => 'required|in:hall,coach,shop,product',
        ]);
        if($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 ,  $validator->errors()->first(), $validator->errors()->first(), null, $request->lang );
            return response()->json($response , 406);
        }
        $favorite = Favorite::where('product_id' , $request->fav_id)->where('type',$request->type)->first();
        if($favorite){
            $favorite->delete();
            $response = APIHelpers::createApiResponse(false , 200 ,  'Deleted ', 'تم الحذف' , null, $request->lang);
            return response()->json($response , 200);
        }else{
            $response = APIHelpers::createApiResponse(true , 406 ,  'this favorite not exist', 'هذا المفضل غير موجود بالمفضله' , null, $request->lang );
            return response()->json($response , 406);
        }
    }

    public function getfavorites(Request $request){
        $user = auth()->user();
        $lang = $request->lang ;
        if($user->active == 0){
            $response = APIHelpers::createApiResponse(true , 406 ,  'تم حظر حسابك', 'تم حظر حسابك' , null, $request->lang );
            return response()->json($response , 406);
        }else {
            $hall_favorites = Favorite::select('id','product_id','user_id')
                                 ->with('Hall')
                                 ->where('type','hall')
                                 ->where('user_id', $user->id)
                                 ->orderBy('id','desc')
                                 ->get();
            $halls = [];
            foreach ($hall_favorites as $key => $row){
                $halls[$key]['id'] = $row->id;
                $halls[$key]['hall_id'] = $row->product_id;
                if($lang == 'ar'){
                    $halls[$key]['title'] = $row->Hall->name;
                }else{
                    $halls[$key]['title'] = $row->Hall->name_en;
                }
                $halls[$key]['image'] = $row->Hall->image;
                $halls[$key]['logo'] = $row->Hall->logo;
                $halls[$key]['rate'] = $row->Hall->rate ;
                $halls[$key]['favorite'] = true;
            }
            $coach_favorites = Favorite::select('id','product_id','user_id')
                ->with('Coach')
                ->where('type','coach')
                ->where('user_id', $user->id)
                ->orderBy('id','desc')
                ->get();
            $coaches = [];
            foreach ($coach_favorites as $key => $row){
                $coaches[$key]['id'] = $row->id;
                $coaches[$key]['product_id'] = $row->product_id;
                if($lang == 'ar'){
                    $coaches[$key]['title'] = $row->Coach->name;
                }else{
                    $coaches[$key]['title'] = $row->Coach->name_en;
                }
                $coaches[$key]['image'] = $row->Coach->image;
                $coaches[$key]['rate'] = $row->Coach->rate ;
                $coaches[$key]['available'] = $row->Coach->available ;
                $coaches[$key]['favorite'] = true;
            }

            $shop_favorites = Favorite::select('id','product_id','user_id')
                ->with('Shop')
                ->where('type','shop')
                ->where('user_id', $user->id)
                ->orderBy('id','desc')
                ->get();
            $shops = [];
            foreach ($shop_favorites as $key => $row){
                $shops[$key]['id'] = $row->id;
                $shops[$key]['product_id'] = $row->product_id;
                if($lang == 'ar'){
                    $shops[$key]['title'] = $row->Shop->name_ar;
                }else{
                    $shops[$key]['title'] = $row->Shop->name_en;
                }
                $shops[$key]['logo'] = $row->Shop->logo;
                $shops[$key]['cover'] = $row->Shop->cover;
                $shops[$key]['favorite'] = true;
            }
            if( count($halls) > 0 ||  count($coaches) > 0 ) {
                $response = APIHelpers::createApiResponse(false, 200, '', '', array('halls'=> $halls , 'coaches'=> $coaches, 'shops'=> $shops), $request->lang);
            }else{
                $response = APIHelpers::createApiResponse(false, 200, 'no item favorite to show', 'لا يوجد عناصر للعرض', null, $request->lang);
            }
            return response()->json($response, 200);
        }
    }
}
