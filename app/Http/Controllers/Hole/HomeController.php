<?php
namespace App\Http\Controllers\Hole;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ContactUs;
use App\User;
use App\Product;
use App\Plan;
use App\Ad;

class HomeController extends AdminController{

    // get all contact us messages
    public function home(){
        return view('hole.home');
    }

}
