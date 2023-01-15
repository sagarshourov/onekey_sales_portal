<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class AuthController  extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $input['password'] = bcrypt($input['email']);
        $input['first_name'] = $input['first_name'];
        $input['last_name'] = $input['last_name'];
        $input['email'] = $input['email'];
        $input['is_admin'] = 1;


        $user = User::create($input);



        return $this->sendResponse($user, 'User Registered successfully.');
    }
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('authToken')->accessToken;
            $success['user'] = $user;
          ///  $success['profile_image'] = Files::where(['user_id' => $user->id, 'doc_type' => 2])->first('file_path');

            return $this->sendResponse($success, 'User login successfully.');
        } else {

            return $this->sendError('Unauthorized.', ['error' => 'User email or password is wrong !']);
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
        //
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

    public function userinfo($id)
    {

        if ($id == 0) {
            $user_id = Auth::id();
        } else {
            $user_id = $id;
        }


        $users =   User::find($user_id);
        $return['id'] = $users->id;

        $return['first_name'] = $users->first_name;
        $return['middle_name'] = $users->middle_name;
        $return['last_name'] = $users->last_name;
        $return['profile_image'] = Files::where(['user_id' => $user_id, 'doc_type' => 2])->first('file_path');
        $return['email'] = $users->email;
        $return['user_phone'] = $users->user_phone;
        $return['whatsapp'] = $users->whatsapp;
        $return['birth'] = $users->birth_date;
        $return['gendar'] = $users->gendar;

        return $this->sendResponse($return, 'Users Info retrieved successfully.');
    }


}
