<?php

namespace App\Http\Controllers;

use App\Models\Calls;
use App\Models\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotiController extends BaseController
{



    private function get_noti()
    {

        $user = Auth::user();

        //   return   $user;

        // if ($user->is_admin == 3) {
        //     return Notifications::where('to_id', $user->id)->with(['types', 'user', 'receiver'])->orderBy('id', 'DESC')->get();
        // } else {
        //     return Notifications::with(['types', 'user', 'receiver'])->orderBy('id', 'DESC')->get();
        // }


        $user = Auth::user();

        if ($user->is_admin == 1 || $user->is_admin == 2) {
            return Notifications::where('to_id', $user->id)->orWhere('to_id', null)->with(['types', 'user', 'receiver', 'admin'])->orderBy('id', 'DESC')->get();
        } else {
            return Notifications::where('to_id', $user->id)->with(['types', 'user', 'receiver', 'admin'])->orderBy('id', 'DESC')->get();
        }
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return $this->sendResponse($this->get_noti(), 'Notifications Retrieve successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {



        // $noti = Notifications::where(['call_id' => $request->call_id, 'type' => $request->type])->first();
        // $user = Auth::user();
        // //return $this->sendResponse($noti, 'Add calls successfully.');
        // if ($noti == null || $noti->count() == 0) {

        //     $input = $request->all();
        //     $input['user_id'] =  $request->user_id;
        //     Notifications::create($input);
        // } else {
        //     Notifications::where('id', (int)  $noti->id)->update(['is_read' => null, 'user_id' => $request->user_id]);
        // }


        $input = $request->all();
        $input['user_id'] =  $request->user_id;
        Notifications::create($input);


        return $this->sendResponse($this->get_noti(), 'Notifications Add successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //




        if ($request->type == 1) { // only viewed
            $noti = Notifications::where('id', (int)  $id)->update(['is_read' => $request->is_read]);

            return $this->sendResponse(array('noti' => $this->get_noti(), 'call' => Calls::withTrashed()->with(['user'])->where('id', (int)$request->call_id)->first()), 'Notifications updated  successfully.');
        }
        if ($request->type == 2) {  // reject 

            $noti = Notifications::where('id', (int)  $id)->update(['is_read' => $request->is_read, 'content'=>'The transfer was rejected', 'note' => $request->note, 'admin_id' => $request->admin_id, 'approve' => 0]);
            return $this->sendResponse(array('noti' => $this->get_noti(), 'call' => Calls::withTrashed()->with(['user'])->where('id', (int)$request->call_id)->first()), 'Notifications updated  successfully.');
        } else {
            $noti = Notifications::where('id', (int)  $id)->update(['is_read' => $request->is_read]);
            return $this->sendResponse(array('noti' => $this->get_noti(), 'call' => ''), 'Notifications updated successfully.');
        }
    }
    public function read_all_noti(Request $request)
    {

        $noti = Notifications::where('to_id', (int)  $request->user_id)->orWhere('to_id', null)->update(['is_read' => $request->is_read]);
        return $this->sendResponse(array('noti' => $this->get_noti(), 'call' => ''), 'Notifications updated successfully.');
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
        Notifications::find($id)->forceDelete();

        return $this->sendResponse($this->get_noti(), 'Notification deleted  successfully.');
    }
}
