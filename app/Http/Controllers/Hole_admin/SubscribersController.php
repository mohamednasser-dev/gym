<?php
namespace App\Http\Controllers\Hole_admin;
use App\Hole;
use App\Hole_booking;
use App\Hole_booking_detail;
use App\Http\Controllers\Controller;
use App\Income;
use App\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Hole_branch;


class SubscribersController extends Controller{

    // get all contact us messages
    public function index($type){
        $id = auth()->guard('hole')->user()->id;
        $bookings = Hole_booking::where('hole_id',$id)->where('deleted','0')->get();
        $booking_ids = Hole_booking::where('hole_id',$id)->select('id')->get()->toArray();
        $data = Reservation::whereIn('booking_id',$booking_ids)->where('status',$type)->orderBy('created_at','desc')->get();
        return view('hole_admin.subscribers.index' ,compact('data','bookings'));
    }

    public function end($id){
        $data['status'] = 'ended';
        Reservation::where('id',$id)->update($data);
        session()->flash('success', trans('messages.status_changed'));
        return back();
    }

    public function resubscribe($id){
        $id = auth()->guard('hole')->user()->id;
        $data['status'] = 'ended';
        Reservation::where('id',$id)->update($data);
        session()->flash('success', trans('messages.status_changed'));
        return back();
    }

    public function re_new(Request $request){
        $reservation = Reservation::find($request->reserv_id);

        //to get booking monthes to generate expire date ...
        $booking = Hole_booking::find($request->booking_id);
        $mytime = Carbon::now();
        $today =  Carbon::parse($mytime->toDateTimeString())->format('Y-m-d H:i');
        $final_date = Carbon::createFromFormat('Y-m-d H:i', $today);
        $final_expire_date = $final_date->addMonths($booking->months_num);

        $data['expire_date'] = $final_expire_date ;
        $data['booking_id'] = $request->booking_id ;
        $data['renew_num'] = $reservation->renew_num + 1 ;
        $data['status'] = 'start';
        $updated_reserv = Reservation::create($data);

        if($updated_reserv == 1 ){
            if($request->add_money == 'add_money'){

                if($booking->is_discount == '1'){
                    $income_Data['price'] = $booking->discount_price ;
                }else{
                    $income_Data['price'] = $booking->price ;
                }
                $income_Data['type'] = 'hall' ;
                $income_Data['user_id'] = $reservation->user_id ;
                $income_Data['reservation_id'] = $request->reserv_id ;
                $income_Data['booking_id'] = $request->booking_id ;
                Income::create($income_Data);
                session()->flash('success', trans('messages.reservation_re_new_money'));
                return back();
            }
        }
        session()->flash('success', trans('messages.reservation_re_new'));
        return back();
    }


}
