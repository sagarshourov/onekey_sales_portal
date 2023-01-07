<?php

namespace App\Http\Controllers;

use App\Models\Calls;
use App\Models\CallsExtra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CallsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private function get_calls()
    {


        $user = Auth::user();

        //   return   $user;

        if ($user->is_admin == 3) {
            return Calls::where('user_id', $user->id)->with(['extra', 'results', 'fresults', 'priority', 'status', 'package', 'cancel_reason', 'user'])->get();
        } else {
            return Calls::with(['extra', 'results', 'fresults', 'priority', 'status', 'package', 'cancel_reason', 'user' => function ($q) {
                $q->orderBy('id', 'DESC');
            }])->get();
        }
    }




    public function check($field, $value)
    {
        $input['email'] = $value;

        // Must not already exist in the `email` column of `users` table
        $rules = array('email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i|unique:calls');

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $call =  Calls::withTrashed()->where('email', $input['email'])->first();
            return $this->sendError($validator->errors(), $call);
        } else {
            // Register the new user or whatever.
            return $this->sendResponse([], 'Email validate.');
        }
    }



    public function index()
    {
        //

        return $this->sendResponse($this->get_calls(), 'Retrieve successfully.');
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


    private function extra_insert($data, $fields)
    {

        if (count($fields) == 0) {
            return;
        }

        foreach ($fields as $value) {
            if (isset($data[$value])) {
                CallsExtra::create([
                    'call_id' => (int) $data['id'],
                    'field' => $value,
                    'value' => $data[$value],
                ]);
            }
        }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $input = $request->all();






        if (isset($input['id'])) {
            $id =  $input['id'];
            $data = Calls::updateOrCreate(
                ['id' =>  (int) $id],
                $input
            );

            //  $this->extra_insert($input, array('note', 'last_status_notes'));

            return $this->sendResponse($this->get_calls(), 'Update successfully.');
        } else {

            $messages = [
                'unique' => 'taken',
            ];

            $validator = Validator::make($input, [
                'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i|unique:calls',
            ],  $messages);


            if ($validator->fails()) {
                $call =  Calls::withTrashed()->where('email', $input['email'])->first();
                return $this->sendError($validator->errors(), $call);
                //return $this->sendError('Validation Error.', $validator->errors());
            }


            Calls::create($input);
            return $this->sendResponse($this->get_calls(), 'Add calls successfully.');
        }
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

        if ($id == 0) {
            Calls::whereIn('id', $request->ids)->update([$request->name => $request->value]);
            return $this->sendResponse($this->get_calls(), 'Bulk Update Call successfully.');
        } else {


            if ($request->type == 2) {
                $input['call_id'] = (int) $id;
                $input['field'] = $request->name;
                $input['value'] = $request->value;
                CallsExtra::create($input);
            }

            Calls::withTrashed()->where('id', (int)  $id)
                ->update([$request->name => $request->value]);





            return $this->sendResponse($this->get_calls(), 'Update Call successfully.');
        }

        //

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //

        //Calls::find($id)->forceDelete();

        Calls::whereIn('id', $request->all())->forceDelete();

        return $this->sendResponse($this->get_calls(), 'Delete Call successfully.');
    }


    public function events()
    {

        $user = Auth::user();


        $calls =   Calls::where('user_id', $user->id)->get(['follow_up_date', 'first_name', 'last_name', 'note']);

        return $this->sendResponse($calls, 'Events Retrieve successfully.');
    }
}
