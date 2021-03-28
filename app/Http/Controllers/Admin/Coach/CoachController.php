<?php

namespace App\Http\Controllers\Admin\Coach;

use App\Coach;
use App\Hole;
use App\Hole_time_work;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use JD\Cloudder\Facades\Cloudder;

class CoachController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Coach::where('deleted','0')->get();
        return view('coach.coach_users.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('coach.coach_users.create');
    }
    public function famous_coaches(){
        $data = Coach::where('famous','1')->where('deleted','0')->get();
        return view('coach.coach_users.index',compact('data'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate(\request(),
            [
                'name' => 'required',
                'email' => 'required',
                'about_coach' => '',
                'password' => 'required|numeric',
                'image' => 'required',
                'time_from' => 'required',
                'time_to' => 'required',
            ]);
        if($request->password){
            $data['password'] = Hash::make($request->password);
        }
        $data['verified'] = 1;
        if($request->image != null){
            $logo = $request->file('image')->getRealPath();
            Cloudder::upload($logo, null);
            $imagereturned = Cloudder::getResult();
            $image_id = $imagereturned['public_id'];
            $image_format = $imagereturned['format'];
            $image_new_logo = $image_id.'.'.$image_format;
            $data['image'] = $image_new_logo ;
        }
        $coach = Coach::create($data);
        session()->flash('success', trans('messages.added_s'));
        return redirect( route('coaches.show'));
    }
// change status
    public function change_status(Request $request){
        $user = Coach::find($request->id);
        $user->status = $request->status;
        $user->save();
        return redirect()->back();
    }
    public function make_famous(Request $request, $id)
    {
        $coach = Coach::find($id);
        if ($coach->famous == '1') {
            $data['famous'] = '0';
            Coach::where('id', $id)->update($data);
            session()->flash('success', trans('messages.famous_done_coaches'));
        } else {
            $data['famous'] = '1';
            Coach::where('id', $id)->update($data);
            session()->flash('success', trans('messages.famous_done'));
        }
        return back();
    }
    public function show($id)
    {
        $data = Coach::where('id',$id)->first();
        return view('coach.coach_users.details',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Coach::where('id',$id)->first();
        return view('coach.coach_users.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->validate(\request(),
            [
                'name' => 'required',
                'email' => 'required',
                'about_coach' => '',
                'time_from' => 'required',
                'time_to' => 'required',
            ]);
        if($request->password){
            $data['password'] = Hash::make($request->password);
        }
        if($request->image != null){
            $logo = $request->file('image')->getRealPath();
            Cloudder::upload($logo, null);
            $imagereturned = Cloudder::getResult();
            $image_id = $imagereturned['public_id'];
            $image_format = $imagereturned['format'];
            $image_new_logo = $image_id.'.'.$image_format;
            $data['image'] = $image_new_logo ;
        }
        Coach::where('id',$id)->update($data);
        session()->flash('success', trans('messages.updated_s'));
        return redirect( route('coaches.show'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
