<?php
namespace App\Http\Controllers\Hole_admin;
use App\Admin;
use App\Hole;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\ContactUs;
use App\User;
use App\Product;
use App\Plan;
use App\Ad;
use Illuminate\Support\Facades\Hash;

class LoginController extends AdminController{

//    public function __construct()
//    {
//        $this->middleware('auth:hole', ['except' => ['getlogin' , 'postlogin']]);
//    }
    // get login page
    public function getlogin(){
        return view('hole_admin.login');
    }

    // post login
    public function postlogin(Request $request){
        $credentials = request(['email', 'password']);
        if (Auth::guard('hole')->attempt($credentials)) {
            $user = Auth::guard('hole')->user();
            return redirect('/admin-panel/hole/home');
        } else {
            session()->flash('success', trans('messages.email_pass_invalied'));
            return view('admin.login');
        }
    }

    public function logout(){
        $user = Auth::guard('hole')->user();
        Auth::guard('hole')->logout();
        return redirect('/login');
    }

}
