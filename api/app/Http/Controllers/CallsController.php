<?php

namespace App\Http\Controllers;

use App\Models\Calls;
use App\Models\CallsExtra;
use App\Models\ExtraGroups;
use App\Models\ExtraValues;
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
            return Calls::where('user_id', $user->id)->with(['extra.values', 'goal', 'marital_status', 'want_to_study', 'assigned_to', 'applying_for', 'section', 'results', 'follow_up_call_results', 'priority', 'status', 'package', 'cancel_reason', 'user'])->get();
        } else {
            return Calls::with(['extra.values', 'goal', 'marital_status', 'want_to_study', 'assigned_to', 'applying_for',  'section', 'results', 'follow_up_call_results', 'priority', 'status', 'package', 'cancel_reason', 'user' => function ($q) {
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

        return $this->sendResponse($this->get_calls(), 'Calls Retrieve successfully.');
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


    // private function extra_insert($data, $fields)
    // {

    //     if (count($fields) == 0) {
    //         return;
    //     }

    //     foreach ($fields as $value) {
    //         if (isset($data[$value])) {
    //             CallsExtra::create([
    //                 'call_id' => (int) $data['id'],
    //                 'field' => $value,
    //                 'value' => $data[$value],
    //             ]);
    //         }
    //     }
    // }

    private function delete_extra($call_id, $group)
    {
        $all = ExtraGroups::where(['call_id' => $call_id, 'groups' => $group])->get();
        foreach ($all as $child) {
            $parent = ExtraGroups::find($child->id);
            $parent->values()->forceDelete();
            $parent->delete();
        }
    }


    private function extra_single($data, $group, $call_id)
    {
        if (count($data) == 0) {
            return;
        }

        $this->delete_extra($call_id, $group);

        foreach ($data as $key => $groups) {

            $ext = ExtraGroups::create([
                'call_id' => (int) $call_id,
                'groups' => $group
            ]);
            foreach ($groups as $field => $val) {
                ExtraValues::create([
                    'field' => $field,
                    'value' => $val,
                    'ext_id' => $ext->id
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
        // return $this->sendResponse($input, 'Calls add  successfully.');

        if (isset($input['id'])) {
            $id =  $input['id'];
            $follow = $input['follow_up'];
            //unset($input['follow_up']);
            $end = end($follow);
            $input['follow_up_date'] = $end['follow_up_date'];
            $input['follow_up_notes'] = $end['follow_up_notes'];
            $data = Calls::updateOrCreate(
                ['id' =>  (int) $id],
                $input
            );
            $this->extra_single($input['follow_up'], 'follow_up',  $id);
            $this->extra_single($input['con_gpa'], 'con_gpa',  $id);


            //  $this->extra_insert($input, array('note', 'last_status_notes'));
            return $this->sendResponse($this->get_calls(), 'Call Update successfully.');
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
            $n = Calls::create($input);
            $follow = $input['follow_up'];
            //unset($input['follow_up']);

            $end = end($follow);
            $input['follow_up_date'] = $end['follow_up_date'];
            $input['follow_up_notes'] = $end['follow_up_notes'];
            $this->extra_single($input['follow_up'], 'follow_up', $n->id);
            $this->extra_single($input['con_gpa'], 'con_gpa',  $n->id);
            return $this->sendResponse($this->get_calls(), 'Calls add successfully.');
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
            $call = Calls::find($id);
            if ($request->type == 2) {
                $input['call_id'] = (int) $id;
                $input['field'] = $request->name;
                $input['value'] =  $call[$request->name];
                CallsExtra::create($input);
            } else if ($request->type == 3) {
                Calls::withTrashed()->where('id', (int)  $id)
                    ->update([$request->name => $request->value, 'user_id' => $request->user_id]);
            } else {
                Calls::withTrashed()->where('id', (int)  $id)
                    ->update([$request->name => $request->value]);
            }

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

        return $this->sendResponse($this->get_calls(), 'Call deleted successfully.');
    }


    public function events()
    {
        $user = Auth::user();

        if ($user->is_admin == 3) {
            $call_ids = Calls::where('user_id', $user->id)->pluck('id')
                ->toArray();

            $call =  ExtraGroups::whereIn('call_id',  $call_ids)->with(['values', 'calls'])->get();
        } else {
            $call =  ExtraGroups::with(['values', 'calls'])->get();
        }
        // $calls =  Calls::where('user_id', $user->id)->get(['id', 'follow_up_date', 'first_name', 'last_name', 'memo']);
        return $this->sendResponse($call, 'Events Retrieve successfully.');
    }


    public function call_export()
    {
        return $this->sendResponse([], 'call Export successfully.');
    }
}
