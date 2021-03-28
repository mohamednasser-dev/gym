<?php
namespace App\Http\Controllers\Hole_admin;
use App\Hole;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller{

    // get all contact us messages
    public function home(){

        return view('hole_admin.home');
    }

    // get profile
    public function profile(){
        $admin = Auth::guard('hole')->user();
        $data['name'] = $admin->name;
        $data['email'] = $admin->email;
        return view('hole_admin.profile' , ['data' => $data]);
    }

    // update profile
    public function updateprofile(Request $request){
        $current_admin_id =  Auth::guard('hole')->user()->id;
        $check_manager_email = Hole::where('email' , $request->email)->where('id' , '!=' , $current_admin_id)->first();
        if($check_manager_email){
            return redirect()->back()->with('status' , 'Email Exists Before');
        }

        $current_manager = Hole::find($current_admin_id);
        $current_manager->name = $request->name;
        $current_manager->email = $request->email;
        if($request->password){
            $current_manager->password = Hash::make($request->password);
        }
        $current_manager->save();
        session()->flash('success', trans('messages.updated_s'));
        return redirect()->back();
    }

}
