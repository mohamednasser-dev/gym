<?php
namespace App\Http\Controllers\Hole;
use App\Hole;
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
                'password' => 'required|numeric',
                'logo' => 'required',
            ]);

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

        Hole::create($data);
        session()->flash('success', trans('messages.added_s'));
        return redirect( route('holes.show'));
    }
    // block user
    public function block(Request $request){
        $user = Hole::find($request->id);
        $user->active = $request->status;
        $user->save();
        return redirect()->back();
    }
    // active user
    public function active(Request $request){
        $user = Hole::find($request->id);
        $user->active = 1;
        $user->save();
        return redirect()->back();
    }

}
