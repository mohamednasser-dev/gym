<?php
namespace App\Http\Controllers\Hole_admin;
use App\Hole_media;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;

class MediaController extends Controller{

    // get all contact us messages
    public function index(){
        $id = auth()->guard('hole')->user()->id;
        $data = Hole_media::where('hole_id',$id)->orderBy('created_at','desc')->get();
        return view('hole_admin.media.index',compact('data') );
    }

    public function store(Request $request)
    {
        $id = auth()->guard('hole')->user()->id;
        $data = $this->validate(\request(),
            [
                'images' => 'required',
                'type' => 'required|in:image,video',
            ]);
        if($request->type == 'image'){

        }
        foreach ($request->images as $image){
            $image_name = $image->getRealPath();
            $imagereturned = Cloudder::getResult();
            $image_id = $imagereturned['public_id'];
            $image_format = $imagereturned['format'];
            $image_new_name = $image_id.'.'.$image_format;
            $data_image['hole_id'] = $id ;
            $data_image['image'] = $image_new_name ;
            Hole_media::create($data_image);
        }
        session()->flash('success', trans('messages.added_s'));
        return back();
    }
    public function destroy(Request $request){
        Hole_media::where('id',$request->media_id)->delete();
        session()->flash('success', trans('messages.deleted_s'));
        return redirect()->back();
    }
}
