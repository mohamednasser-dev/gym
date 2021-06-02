<?php
namespace App\Http\Controllers\Admin\Store;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Hash;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Http\Request;
use App\Shop;
use App\Setting;
use App\Product;
use Cloudinary;

class ShopController extends AdminController{

    // show shops
    public function index() {
        $data['shops'] = Shop::get();

        return view('store.shop.index', ['data' => $data]);
    }
    public function famous() {
        $data['shops'] = Shop::where('famous','1')->get();
        return view('store.shop.index', ['data' => $data]);
    }

    // get create shop
    public function AddGet(Request $request) {
        return view('store.shop.create');
    }

    // post create shop
    public function AddPost(Request $request) {
        $post = $request->all();
        $request->validate([
            'email' => 'required|unique:shops,email',
            'password' => 'required',
            'name_ar' => 'required',
            'name_en' => 'required',
            'phone' => ''
        ]);

        if($request->file('logo')){
            $image_name = $request->file('logo')->getRealPath();
            Cloudder::upload($image_name, null);
            $imagereturned = Cloudder::getResult();
            $image_id = $imagereturned['public_id'];
            $image_format = $imagereturned['format'];
            $image_new_name = $image_id.'.'.$image_format;
            $post['logo'] = $image_new_name;
        }
        if($request->file('cover')){
            $cover_name = $request->file('cover')->getRealPath();
            Cloudder::upload($cover_name, null);
            $coverreturned = Cloudder::getResult();
            $cover_id = $coverreturned['public_id'];
            $cover_format = $coverreturned['format'];
            $cover_new_name = $cover_id.'.'.$cover_format;
            $post['cover'] = $cover_new_name;
        }
        $post['password'] = Hash::make($request->password);
        Shop::create($post);
        return redirect()->route('shops.show');
    }

    public function make_famous(Request $request, $id)
    {
        $shop = Shop::find($id);
        if ($shop->famous == '1') {
            $data['famous'] = '0';
            Shop::where('id', $id)->update($data);
            session()->flash('success', trans('messages.famous_removed_done_shop'));
        } else {
            $data['famous'] = '1';
            Shop::where('id', $id)->update($data);
            session()->flash('success', trans('messages.famous_done_shop'));
        }
        return back();
    }

    // edit get
    public function EditGet(Shop $store) {
        $data['store'] = $store;

        return view('store.shop.edit', ['data' => $data]);
    }

    // edit post
    public function EditPost(Request $request, Shop $store) {
        $post = $request->all();
        $request->validate([
            'email' => 'required|unique:shops,email,' . $store->id,
            'name_ar' => 'required',
            'name_en' => 'required',
            'phone' => ''
        ]);

        if($request->file('logo')){
            $logo = $store->logo;
            $publicId = substr($logo, 0 ,strrpos($logo, "."));
            Cloudder::delete($publicId);
            $image_name = $request->file('logo')->getRealPath();
            Cloudder::upload($image_name, null);
            $imagereturned = Cloudder::getResult();
            $image_id = $imagereturned['public_id'];
            $image_format = $imagereturned['format'];
            $image_new_name = $image_id.'.'.$image_format;
            $post['logo'] = $image_new_name;
        }
        if($request->file('cover')){
            $cover = $store->cover;
            $publicId = substr($cover, 0 ,strrpos($cover, "."));
            Cloudder::delete($publicId);
            $cover_name = $request->file('cover')->getRealPath();
            Cloudder::upload($cover_name, null);
            $coverreturned = Cloudder::getResult();
            $cover_id = $coverreturned['public_id'];
            $cover_format = $coverreturned['format'];
            $cover_new_name = $cover_id.'.'.$cover_format;
            $post['cover'] = $cover_new_name;
        }

        if (isset($request->password) && !empty($request->password)) {
            $post['password'] = Hash::make($request->password);
        }else {
            $post['password'] = $store->password;
        }
        $store->update($post);

        return redirect()->route('shops.show');
    }

    // store details
    public function details(Shop $store) {
        $data['store'] = $store;

        return view('store.shop.details', ['data' => $data]);
    }

    // action
    public function action(Shop $store, $status) {
        $store->update(['status' => $status]);

        return redirect()->back();
    }

    // get product offers
    public function getProductOffers() {
        $data['offer_image'] = Setting::where('id', 1)->select('offer_image')->first()['offer_image'];
        $data['products'] = Product::where('deleted', 0)->where('free', 1)->orderBy('id' , 'desc')->get();

        return view('store.product.offers', ['data' => $data]);
    }

    // update offer image
    public function updateOfferImage(Request $request) {
        $setting = Setting::where('id', 1)->select('id', 'offer_image')->first();
        if($request->offer_image != null){
			if ($request->file('offer_image')->getSize()) {
				$uploadedFileUrl = Cloudinary::upload($request->file('offer_image')->getRealPath());
				$image_id2 = $uploadedFileUrl->getPublicId();
				$image_format2 = $uploadedFileUrl->getExtension();
				$image_new_story = $image_id2.'.'.$image_format2;
				$setting->offer_image = $image_new_story ;
                $setting->save();
			}
        }

        return redirect()->back()->with('success', __('messages.updated_s'));
    }

    // action free product (add - remove)
    public function actionFreeProduct(Product $product, $status) {
        
        $product->free = $status;
        $product->save();
        $statusText = __('messages.added_to_offer_buy_two_get_one');
        if ($status == 0) {
            $statusText = __('messages.removed_from_offer_buy_two_get_one');
        }

        return redirect()->route('shops.products.offers')
            ->with('success', $statusText);
    }


}
