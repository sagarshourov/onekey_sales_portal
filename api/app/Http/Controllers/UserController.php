<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private function users()
    {
        $users = User::all();
        return $users;
    }


    public function index()
    {
        //
        return $this->sendResponse($this->users(), 'User retrive successfully.');
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
        //
        $input = $request->all();


        $validator = Validator::make($input, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i|unique:users',
        ]);

        if ($validator->fails()) {




            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input['password'] = bcrypt($input['password']);



        User::create($input);
        return $this->sendResponse($this->users(), 'User retrive successfully.');
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

        $input = $request->all();

        if (isset($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        }


        User::updateOrCreate(
            [
                'id'   => $input['id'],
            ],
            $input

        );
        return $this->sendResponse($input, 'User Info Updated successfully.');
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

        User::find($id)->delete();

        return $this->sendResponse($this->users(), 'Delete Call successfully.');
    }
}
