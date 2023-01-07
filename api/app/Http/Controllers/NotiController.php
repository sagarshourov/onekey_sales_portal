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
        return Notifications::with(['types', 'user'])->orderBy('id', 'DESC')->get();
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return $this->sendResponse($this->get_noti(), 'Retrive successfully.');
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



        $noti = Notifications::where(['call_id' => $request->call_id, 'type' => $request->type])->first();

        //return $this->sendResponse($noti, 'Add calls successfully.');
        if ($noti == null || $noti->count() == 0) {
            $user = Auth::user();
            $input = $request->all();
            $input['user_id'] =  $user->id;
            Notifications::create($input);
        } else {
            Notifications::where('id', (int)  $noti->id)->update(['is_read' => null]);
        }
        return $this->sendResponse($this->get_noti(), 'Add calls successfully.');
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


        $noti = Notifications::where('id', (int)  $id)->update(['is_read' => $request->is_read]);


        if ($request->type == 1) {
            return $this->sendResponse(array('noti' => $this->get_noti(), 'call' => Calls::withTrashed()->where('id', (int)$request->call_id)->first()), 'Update Notifictions successfully.');
        } else {
            return $this->sendResponse(array('noti' => $this->get_noti(), 'call' => ''), 'Update Notifictions  successfully.');
        }
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

        return $this->sendResponse($this->get_noti(), 'Delete Notification successfully.');
    }
}
