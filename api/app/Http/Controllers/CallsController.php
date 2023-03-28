<?php

namespace App\Http\Controllers;

use App\Models\Calls;
use App\Models\CallsExtra;
use App\Models\ExtraGroups;
use App\Models\ExtraValues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CallImport;
use App\Models\Package;
use App\Models\Sections;
use App\Models\Status;

use Illuminate\Support\Facades\Http;

class CallsController extends BaseController
{


    private function clean($string)
    {

        $a =  explode('-', $string);

        if (isset($a[0])) {
            $strig = str_replace(' ', '-', $a[0]); // Replaces all spaces with hyphens.

            return preg_replace('/[^A-Za-z0-9\-]/', '', $strig); // Removes special chars.


        } else {

            return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars. 
        }
    }
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
            return Calls::where(['assigned_to' => $user->id, 'results' => 3])->with(['extra.values', 'history.user.profile', 'goal', 'marital_status', 'want_to_study', 'assigned_to', 'applying_for', 'section', 'results', 'follow_up_call_results', 'priority', 'statu', 'package', 'cancel_reason', 'user'])->orderBy('id', 'ASC')->get();
        } else {
            return Calls::where('results', 3)->with(['extra.values', 'history.user.profile', 'goal', 'marital_status', 'want_to_study', 'assigned_to', 'applying_for',  'section', 'results', 'follow_up_call_results', 'priority', 'statu', 'package', 'cancel_reason', 'user'])->orderBy('id', 'ASC')->get();
        }
    }

    public function reports($emp_id, $off)
    {

        $user = Auth::user();

        //   return   $user;

        if ($user->is_admin == 3) {
            $calls = Calls::where(['assigned_to' => $user->id])->with(['extra.values', 'history.user.profile', 'goal', 'marital_status', 'want_to_study', 'assigned_to', 'applying_for', 'section', 'results', 'follow_up_call_results', 'priority', 'statu', 'package', 'cancel_reason', 'user'])->orderBy('id', 'desc')->offset($off)->limit(20)->get();
        } else {
            $calls = Calls::where(['assigned_to' => $emp_id])->with(['extra.values', 'history.user.profile', 'goal', 'marital_status', 'want_to_study', 'assigned_to', 'applying_for',  'section', 'results', 'follow_up_call_results', 'priority', 'statu', 'package', 'cancel_reason', 'user'])->orderBy('id', 'desc')->offset($off)->limit(20)->get();
        }


        return $this->sendResponse($calls, 'Calls Retrieve successfully.');
    }







    private function get_filter_cal($field, $value)
    {
        $user = Auth::user();

        //   return   $user;

        if ($user->is_admin == 3) {
            return Calls::where(['assigned_to' => $user->id, $field => $value])->with(['extra.values', 'history.user.profile', 'goal', 'marital_status', 'want_to_study', 'assigned_to', 'applying_for', 'section', 'results', 'follow_up_call_results', 'priority', 'statu', 'package', 'cancel_reason', 'user'])->orderBy('id', 'desc')->get();
        } else {
            return Calls::where($field, $value)->with(['extra.values', 'history.user.profile', 'goal', 'marital_status', 'want_to_study', 'assigned_to', 'applying_for',  'section', 'results', 'follow_up_call_results', 'priority', 'statu', 'package', 'cancel_reason', 'user'])->orderBy('id', 'desc')->get();
        }
    }




    public function filter($field, $value)
    {
        $filter = $this->get_filter_cal($field, $value);


        return $this->sendResponse($filter, 'Retrieve calls.');
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

    public function register_api($data = array())
    {


        $endpoint = "https://api.onekeyclient.us/api/register_api";


        $response = Http::post($endpoint, $data);




        return   $data;
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


    private function extra_group($data, $group, $call_id)
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

    private function extra_single($filed, $value, $user_id, $call_id, $call_user_id)
    {
        $user = Auth::user();
        if ($call_user_id == $user->id) {
        } else {
            $input['call_id'] = (int) $call_id;
            $input['field'] = $filed;
            $input['value'] =  $value;
            $input['user_id'] = (int) $user_id;
            CallsExtra::create($input);
        }


        // $input['call_id'] = (int) $call_id;
        // $input['field'] = $filed;
        // $input['value'] =  $value . $user_id;
        // $input['user_id'] =  $user->id;
        // CallsExtra::create($input);
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
            if (isset($input['follow_up'])) {
                $follow = $input['follow_up'];
                //unset($input['follow_up']);
                $end = end($follow);
                $input['follow_up_date'] = $end['follow_up_date'];
                $input['follow_up_notes'] = $end['follow_up_notes'];
                if ($end['f_results'] == 1 && $input['cancel_reason'] != 0) {
                    $input['results'] = 1;
                } else if ($end['f_results'] == 2 && isset($input['f_results']) && $input['f_results'] == 2) {
                    $input['results'] = 2;
                    $this->register_api(Calls::where('id', $id)->select('first_name', 'last_name', 'email', 'phone_number')->get());
                }


                $this->extra_group($input['follow_up'], 'follow_up',  $id);
            }
            isset($input['con_gpa']) &&  $this->extra_group($input['con_gpa'], 'con_gpa',  $id);
            isset($input['suppose']) &&  $this->extra_group($input['suppose'], 'suppose', $id);
            isset($input['my_step']) &&  $this->extra_group($input['my_step'], 'my_step',  $id);
            $this->extra_single('feedbacks', $input['feedbacks'], $input['user_id'], $input['id'], $input['assigned_to']);
            unset($input['user_id']);

            if (isset($input['results']) && $input['results'] == 4) {
                $input['results'] = 3;
                $input['sections'] = 5;
            } else if (isset($input['results']) && $input['results'] == 2) {
                $this->register_api(Calls::where('id', $id)->select('first_name', 'last_name', 'email', 'phone_number')->get());
            } else if ($input['f_results'] == 1 && $input['cancel_reason'] != 0) {
                $input['results'] = 1;
            }


            if (isset($input['f_results']) && $input['f_results'] == 4) {
                $input['results'] = 3;
                // $input['sections'] = 5;
            } else if (isset($input['f_results']) && $input['f_results'] == 2) {
                $input['results'] = 2;
                $this->register_api(Calls::where('id', $id)->select('first_name', 'last_name', 'email', 'phone_number')->get());
            }


            $data = Calls::updateOrCreate(
                ['id' =>  (int) $id],
                $input
            );

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


            if ($input['f_results'] == 4) {
                $input['results'] = 3;
                $input['sections'] = 5;
            }

            // else if ($input['results'] == 3) {
            //     $input['sections'] = null;
            // }

            $n = Calls::create($input);
            $follow = $input['follow_up'];
            //unset($input['follow_up']);

            $end = end($follow);
             $input['follow_up_date'] = $end['follow_up_date'];
            $input['follow_up_notes'] = $end['follow_up_notes'];

            isset($input['follow_up']) &&  $this->extra_group($input['follow_up'], 'follow_up', $n->id);
            isset($input['con_gpa']) &&  $this->extra_group($input['con_gpa'], 'con_gpa',  $n->id);

            isset($input['suppose']) &&  $this->extra_group($input['suppose'], 'suppose', $n->id);
            isset($input['my_step']) &&  $this->extra_group($input['my_step'], 'my_step',  $n->id);

            if ($input['f_results'] == 2) {
                $input['results'] = 2;
                $this->register_api(Calls::where('id',  $n->id)->select('first_name', 'last_name', 'email', 'phone_number')->get());
            }


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
        $call =  Calls::where('id', $id)->with(['extra.values', 'history.user.profile', 'goal', 'marital_status', 'want_to_study', 'assigned_to', 'applying_for', 'section', 'results', 'follow_up_call_results', 'priority', 'statu', 'package', 'cancel_reason', 'user'])->first();

        return $this->sendResponse($call, 'Single Call retrieve successfully.');
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


    public function call_single(Request $request, $id)
    {

        $call = Calls::find($id);

        $this->extra_single($request->name, $call->{$request->name}, $request->user_id, $id, $call->assigned_to);


        $call->{$request->name} = $request->value;

        $call->save();


        $data = Calls::updateOrCreate(
            ['id' =>  (int) $id],
            [$request->name => $request->value]
        );

        // $filed, $value, $user_id, $call_id)

        return $this->sendResponse($this->get_calls(), 'Update Call successfully.');
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
            if ($request->name == 'results' && $request->value == '4') { // when no answer selected its will go no answer section
                Calls::whereIn('id', $request->ids)
                    ->update(['sections' => 5]);
            } else  if ($request->name == 'results' && $request->value == '3') { // when no answer selected its will go no answer section
                Calls::whereIn('id', $request->ids)
                    ->update(['sections' => null, 'results' => 3]);
            } else  if ($request->name == 'results' && $request->value == '2') { // when no answer selected its will go no answer section
                Calls::whereIn('id', $request->ids)
                    ->update(['sections' => null, 'results' => 2]);
                $this->register_api(Calls::whereIn('id', $request->ids)->select('first_name', 'last_name', 'email', 'phone_number')->get());
            } else {
                Calls::whereIn('id', $request->ids)->update([$request->name => $request->value]);
            }

            if ($request->name == 'results') {
                Calls::whereIn('id', $request->ids)
                    ->update(['f_results' => $request->value]);
            }



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
                    ->update([$request->name => $request->value, 'assigned_to' => $request->user_id, 'sections' => null, 'results' => 3, 'assigned_date' => date("Y-m-d H:i:s")]);
            } else  if ($request->name == 'results' && $request->value == '3') { // when no answer selected its will go no answer section
                Calls::where('id', (int)  $id)
                    ->update(['sections' => null, 'results' => 3]);
            } else {

                if ($request->name == 'results' && $request->value == '4') { // when no answer selected its will go no answer section
                    Calls::withTrashed()->where('id', (int)  $id)
                        ->update(['sections' => 5]);
                } else {
                    Calls::withTrashed()->where('id', (int)  $id)
                        ->update([$request->name => $request->value]);
                }
            }


            if ($request->name == 'results' && $request->value == '2') {
                //  $this->register_api(Calls::whereIn('id', $request->ids)->get());

                $this->register_api(Calls::where('id', $id)->select('first_name', 'last_name', 'email', 'phone_number')->get());
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

        // if ($user->is_admin == 3) {
        //     $call_ids = Calls::where('user_id', $user->id)->pluck('id')
        //         ->toArray();

        //     $call =  ExtraGroups::whereIn('call_id',  $call_ids)->with(['values', 'calls'])->get();
        // } else {
        //     $call =  ExtraGroups::with(['values', 'calls'])->get();
        // }
        // // $calls =  Calls::where('user_id', $user->id)->get(['id', 'follow_up_date', 'first_name', 'last_name', 'memo']);
        // return $this->sendResponse($call, 'Events Retrieve successfully.');

        if ($user->is_admin == 3) {

            //$call_ids['follow_up_date'] = Calls::where('user_id', $user->id)->get(['id', 'first_name', 'last_name', 'follow_up_date', 'call_schedule_date']);

            $call_ids['fud'] = Calls::where([['user_id', '=', $user->id], ['follow_up_date', '!=', null]])->get(['id', 'first_name', 'last_name', 'follow_up_date']);
            $call_ids['csd'] = Calls::where([['user_id', '=', $user->id], ['call_schedule_date', '!=', null]])->get(['id', 'first_name', 'last_name', 'call_schedule_date']);
        } else {
            $call_ids['fud'] = Calls::where('follow_up_date', '!=', null)->get(['id', 'first_name', 'last_name', 'follow_up_date']);
            $call_ids['csd'] = Calls::where('follow_up_date', '!=', null)->get(['id', 'first_name', 'last_name', 'call_schedule_date']);
        }

        return $this->sendResponse($call_ids, 'Events Retrieve successfully.');
    }


    public function call_export()
    {
        $calls = Calls::where('results', null)->get()->groupBy('sections');

        return view('call_view', [
            'calls' => $calls,
            'section' => Sections::all(),
            'package' => Package::all(),
            'status' => Status::all(),

        ]);
        return $this->sendResponse($calls, 'call Export successfully.');
    }

    public function import_file(Request $request)
    {
        $file_name = $request->file('file')->getClientOriginalName();
        $file =  $request->file('file')->store('files');

        return $this->sendResponse(array($file, $file_name), 'File Imported successfully.');
    }


    public function import(Request $request)
    {

        if ($request->user_id !== 0) {

            $file_name = $this->clean($request->file_name);

            Excel::import(
                new CallImport($request->user_id, $file_name),
                $request->file_path
            );


            return $this->sendResponse([], 'File Imported successfully.');
        }
    }
}
