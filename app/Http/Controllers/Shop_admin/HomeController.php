<?php
namespace App\Http\Controllers\Shop_admin;

use App\Ad;
use App\Category;
use App\ContactUs;
use App\Http\Controllers\Controller;
use App\MainOrder;
use App\OrderItem;
use App\Product;
use App\Seller;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Shop;

class HomeController extends Controller{

    // get all contact us messages
    public function home(){
        // $data['users'] = User::count();
        // $data['ads'] = Ad::count();
        // $data['categories'] = Category::where('deleted', 0)->count();
        // $data['contact_us'] = ContactUs::count();
        // $data['products_less_than_ten'] = Product::where('deleted' , 0)->where('remaining_quantity' , '<' , 10)->count();
        // $data['most_sold_products']=OrderItem::join('products','products.id', '=','order_items.product_id')
        //     ->leftjoin('orders', function($join) {
        //         $join->on('orders.id', '=', 'order_items.order_id');
        //     })
        //     ->leftjoin('product_multi_options', function($join) {
        //         $join->on('product_multi_options.id', '=', 'order_items.option_id');
        //     })
        //     ->where('main_orders.status', 3)
        //     ->leftjoin('main_orders', function($join) {
        //         $join->on('main_orders.id', '=', 'orders.main_id');
        //     })
        //     ->select('products.id', 'products.multi_options', 'products.price_before_offer', 'products.final_price', 'products.remaining_quantity', 'products.title_en','products.title_ar', DB::raw('SUM(count) as cnt'))
        //     ->addSelect('orders.status')
        //     ->groupBy('orders.status')
        //     ->groupBy('order_items.product_id')
        //     ->groupBy('order_items.option_id')
        //     ->groupBy('products.id')
        //     ->groupBy('products.multi_options')
        //     ->groupBy('products.title_en')
        //     ->groupBy('products.title_ar')
        //     ->groupBy('products.final_price')
        //     ->groupBy('products.remaining_quantity')
        //     ->groupBy('products.price_before_offer')
        //     ->groupBy('product_multi_options.id')
        //     ->groupBy('product_multi_options.product_id')
        //     ->groupBy('product_multi_options.final_price')
        //     ->groupBy('product_multi_options.remaining_quantity')
        //     ->groupBy('product_multi_options.price_before_offer')
        //     ->orderBy('cnt', 'desc')->take(7)->get();

        // $data['most_refund_products']= OrderItem::join('products','products.id', '=','order_items.product_id')
        //     ->where('order_items.status', 6)
        //     ->select('products.id', 'products.multi_options', 'products.remaining_quantity', 'products.title_en','products.title_ar', DB::raw('SUM(count) as cnt'))
        //     ->groupBy('order_items.product_id')
        //     ->groupBy('order_items.option_id')
        //     ->groupBy('products.id')
        //     ->groupBy('products.multi_options')
        //     ->groupBy('products.title_en')
        //     ->groupBy('products.title_ar')
        //     ->groupBy('products.final_price')
        //     ->groupBy('products.remaining_quantity')
        //     ->groupBy('products.price_before_offer')
        //     ->orderBy('cnt', 'desc')->take(7)->get();
        // $data['recent_orders'] = MainOrder::orderBy('id' , 'desc')->take(7)->get();
        // $data['in_progress_orders'] = MainOrder::where('status', 1)->sum('total_price');
        // $data['canceled_orders'] = MainOrder::where('status', 4)->sum('total_price');
        // $data['delivered_orders'] = MainOrder::where('status', 3)->sum('total_price');
        // $data['total_value'] = (double)$data['in_progress_orders'] + (double)$data['canceled_orders'] + (double)$data['delivered_orders'];

        // $data['monthly_canceled_orders'] = MainOrder::select('id', 'created_at')
        //     ->where('status', 4)
        //     ->get()
        //     ->groupBy(function($date) {
        //         return Carbon::parse($date->created_at)->format('m'); // grouping by months
        //     });
        // $data['canceled_orders_count'] = [];
        // $data['canceled_orders_arr'] = [];

        // foreach ($data['monthly_canceled_orders'] as $key => $value) {
        //     $data['canceled_orders_count'][(int)$key] = count($value);
        // }

        // for($i = 1; $i <= 12; $i++){
        //     if(!empty($data['canceled_orders_count'][$i])){
        //         $data['canceled_orders_arr'][$i] = $data['canceled_orders_count'][$i];
        //     }else{
        //         $data['canceled_orders_arr'][$i] = 0;
        //     }
        // }

        // $data['monthly_completed_orders'] = MainOrder::select('id', 'created_at')
        //     ->where('status', 3)
        //     ->get()
        //     ->groupBy(function($date) {
        //         return Carbon::parse($date->created_at)->format('m'); // grouping by months
        //     });
        // $data['completed_orders_count'] = [];
        // $data['completed_orders_arr'] = [];

        // foreach ($data['monthly_completed_orders'] as $key => $value) {
        //     $data['completed_orders_count'][(int)$key] = count($value);
        // }

        // for($i = 1; $i <= 12; $i++){
        //     if(!empty($data['completed_orders_count'][$i])){
        //         $data['completed_orders_arr'][$i] = $data['completed_orders_count'][$i];
        //     }else{
        //         $data['completed_orders_arr'][$i] = 0;
        //     }
        // }

        // $data['monthly_Inprogress_orders'] = MainOrder::select('id', 'created_at')
        //     ->where('status', 1)
        //     ->get()
        //     ->groupBy(function($date) {
        //         return Carbon::parse($date->created_at)->format('m'); // grouping by months
        //     });
        // $data['Inprogress_orders_count'] = [];
        // $data['Inprogress_orders_arr'] = [];

        // foreach ($data['monthly_Inprogress_orders'] as $key => $value) {
        //     $data['Inprogress_orders_count'][(int)$key] = count($value);
        // }

        // for($i = 1; $i <= 12; $i++){
        //     if(!empty($data['Inprogress_orders_count'][$i])){
        //         $data['Inprogress_orders_arr'][$i] = $data['Inprogress_orders_count'][$i];
        //     }else{
        //         $data['Inprogress_orders_arr'][$i] = 0;
        //     }
        // }

        // $data['delivered_orders_cost'] = MainOrder::where('status', 3)->sum('total_price');

        return view('shop_admin.home');
    }

    public function logout(){
        $user = Auth::guard('shop')->user();
        Auth::guard('shop')->logout();
        return redirect('/login');
    }

    // get profile
    public function profile(){
        $admin = Auth::guard('shop')->user();
        $data['name_ar'] = $admin->name_ar;
        $data['name_en'] = $admin->name_en;
        $data['email'] = $admin->email;
        return view('shop_admin.profile' , ['data' => $data]);
    }

    // update profile
    public function updateprofile(Request $request){
        $current_admin_id =  Auth::guard('shop')->user()->id;
        $check_manager_email = Shop::where('email' , $request->email)->where('id' , '!=' , $current_admin_id)->first();
        if($check_manager_email){
            return redirect()->back()->with('status' , 'Email Exists Before');
        }

        $current_manager = Shop::find($current_admin_id);
        $current_manager->name_ar = $request->name_ar;
        $current_manager->name_en = $request->name_en;
        $current_manager->email = $request->email;
        if($request->password){
            $current_manager->password = Hash::make($request->password);
        }
        $current_manager->save();
        session()->flash('success', trans('messages.updated_s'));
        return redirect()->back();
    }
}
