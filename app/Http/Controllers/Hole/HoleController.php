<?php
namespace App\Http\Controllers\Hole;
use App\Hole;
use App\Hole_time_work;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ContactUs;
use App\User;
use App\Product;
use App\Plan;
use App\Ad;
use Illuminate\Support\Facades\Hash;
use JD\Cloudder\Facades\Cloudder;

class HoleController extends AdminController{

    // get all contact us messages
    public function index(){
        $data = Hole::where('deleted','0')->get();
        return view('hole.hole_users.index',compact('data'));
    }

    public function famous_holes(){
        $data = Hole::where('famous','1')->where('deleted','0')->get();
        return view('hole.hole_users.index',compact('data'));
    }

    public function create(){
        return view('hole.hole_users.create');
    }

    public function store(Request $request)
    {
        $data = $this->validate(\request(),
            [
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required|numeric',
                'about_hole' => '',
                'password' => 'required|numeric',
                'logo' => 'required',
            ]);
        if($request->male == 'male'){
            $this->validate(\request(),
                [
                    'male_hole_from' => 'required',
                    'male_hole_to' => 'required'
                ]);
        }
        if($request->male == 'female'){
            $this->validate(\request(),
                [
                    'female_hole_from' => 'required',
                    'female_hole_to' => 'required'
                ]);
        }
        if($request->male == 'mix'){
            $this->validate(\request(),
                [
                    'mix_hole_from' => 'required',
                    'mix_hole_to' => 'required'
                ]);
        }

        if($request->password){
            $data['password'] = Hash::make($request->password);
        }
        if($request->logo != null){
            $logo = $request->file('logo')->getRealPath();
            Cloudder::upload($logo, null);
            $imagereturned = Cloudder::getResult();
            $image_id = $imagereturned['public_id'];
            $image_format = $imagereturned['format'];
            $image_new_logo = $image_id.'.'.$image_format;
            $data['logo'] = $image_new_logo ;
        }
        if($request->cover != null){
            $logo = $request->file('cover')->getRealPath();
            Cloudder::upload($logo, null);
            $imagereturned = Cloudder::getResult();
            $image_id = $imagereturned['public_id'];
            $image_format = $imagereturned['format'];
            $image_new_cover = $image_id.'.'.$image_format;
            $data['cover'] = $image_new_cover ;
        }
        $hole = Hole::create($data);
        if($request->male == 'male'){
            $male_data['time_from'] = $request->male_hole_from;
            $male_data['time_to'] = $request->male_hole_to;
            $male_data['type'] = 'male';
            $male_data['hole_id'] = $hole->id ;
            Hole_time_work::create($male_data);
        }
        if($request->female == 'female'){
            $male_data['time_from'] = $request->female_hole_from;
            $male_data['time_to'] = $request->female_hole_to;
            $male_data['type'] = 'female';
            $male_data['hole_id'] = $hole->id ;
            Hole_time_work::create($male_data);
        }
        if($request->mix == 'mix'){
            $male_data['time_from'] = $request->mix_hole_from;
            $male_data['time_to'] = $request->mix_hole_to;
            $male_data['type'] = 'mix';
            $male_data['hole_id'] = $hole->id ;
            Hole_time_work::create($male_data);
        }
        session()->flash('success', trans('messages.added_s'));
        return redirect( route('holes.show'));
    }
    public function show($id){
        $data = Hole::find($id);
        return view('hole.hole_users.details' ,compact('data'));
    }

    // change status
    public function change_status(Request $request){
        $user = Hole::find($request->id);
        $user->status = $request->status;
        $user->save();
        return redirect()->back();
    }

    public function make_famous(Request $request, $id)
    {
        $hole = Hole::find($id);
        if ($hole->famous == '1') {
            $data['famous'] = '0';
            Hole::where('id', $id)->update($data);
            session()->flash('success', trans('messages.famous_removed_done'));
        } else {
            $data['famous'] = '1';
            Hole::where('id', $id)->update($data);
            session()->flash('success', trans('messages.famous_done'));
        }
        return back();
    }

}
