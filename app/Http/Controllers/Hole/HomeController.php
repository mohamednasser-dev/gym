<?php
namespace App\Http\Controllers\Hole;
use App\Http\Controllers\Admin\AdminController;

class HomeController extends AdminController{

    // get all contact us messages
    public function home(){
        return view('halls.home');
    }

}
