<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
    private function userinfos($id)
    {

        if ($id == 0) {
            $user_id = Auth::id();
        } else {
            $user_id = $id;
        }

        $file =  Files::where(['user_id' => $user_id, 'doc_type' => 2])->first('file_path');


        $users =   User::find($user_id);
        $return['id'] = $users->id;

        $return['first_name'] = $users->first_name;
        $return['middle_name'] = $users->middle_name;
        $return['last_name'] = $users->last_name;
        $return['profile_image'] =  $file !== null ? $file : 'https://gravatar.com/avatar/85f3661b1df4eef564c6083b3bacf33c?s=400&d=mp&r=x';
        $return['email'] = $users->email;
        $return['user_phone'] = $users->user_phone;
        $return['whatsapp'] = $users->whatsapp;
        $return['birth'] = $users->birth_date;
        $return['gendar'] = $users->gendar;
        $return['is_admin'] = $users->is_admin;


        return $return;
    }


    public function userinfo($id)
    {

        return $this->sendResponse($this->userinfos($id), 'Users Info retrieved successfully.');
    }


    private function sendResetEmail($email, $token)
    { //Retrieve the user from the database
        $user = DB::table('users')->where('email', $email)->select('id', 'first_name', 'email')->first(); //Generate, the password reset link. The token generated is embedded in the link$link = config('base_url') . 'password/reset/' . $token . '?email=' . urlencode($user->email);
        $link = env('APP_URL') . '/password/' . $token . '/' . $user->id;
        try {
            //Here send the link with CURL with an external email API         return true;


            Mail::send('email.forget_password', ['link' => $link], function ($message) use ($email) {
                $message->to($email, 'Admin')->subject('Reset Password Notification');
                $message->from("info@onekeyclient.us", 'Admin');
            });
            return true;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function forgot_password(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'pass' => 'required',
            'token' => 'required'
        ]);

        //check if payload is valid before moving on
        if ($validator->fails()) {

            return $this->sendError('email.', ['error' => 'Please complete the form!']);
        }

        $password = $request->pass; // Validate the token
        $tokenData = DB::table('password_resets')
            ->where('token', $request->token)->first();

        if (!isset($tokenData->email)) return  $this->sendError('token.', ['error' => 'Invalid Token']);


        $user = User::where('email', $tokenData->email)->first();

        if (!$user) return  $this->sendError('email.', ['error' => 'Email Not found']);

        $user->password = Hash::make($password);
        $user->update();
        DB::table('password_resets')->where('email', $user->email)
            ->delete();

        return $this->sendResponse($user->password, 'Password Changed successfully.');
    }


    public function forgot(Request $request)
    {

        $user = DB::table('users')->where('email', '=', $request->email)
            ->first(); //Check if the user exists
        if (empty($user) || $user == null) {
            // return redirect()->back()->withErrors(['email' => trans('User does not exist')]);

            return $this->sendError($user, ['error' => 'User does not exist']);
        }

        //Create Password Reset Token
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => Str::random(60),
            'created_at' => Carbon::now()
        ]); //Get the token just created above
        $tokenData = DB::table('password_resets')
            ->where('email', $request->email)->orderBy('created_at', 'desc')->first();

        if ($this->sendResetEmail($request->email, $tokenData->token)) {

            return $this->sendResponse('[success]', ['status' => 'A reset link has been sent to your email address.']);
        } else {


            return $this->sendError('[error]', ['status' => 'A Network Error occurred. Please try again.']);
        }



        // $request->validate(['email' => 'required|email']);

        // $status = Password::sendResetLink(
        //     $request->only('email')
        // );

        // return $this->sendResponse($status, 'Forgot password successfully.');

        // $user = User::find(455);


        // Mail::send('email.new_user', ['user' => $user], function ($message) {
        //     $message->to("sagarbd28@gmail.com", 'Admin')->subject('Forget Password !');
        //     $message->from("info@onekeyclient.us", 'Admin');
        // });



    }



    public function token($user_id)
    {

        $user = User::find($user_id);


        $success['token'] = $user->createToken('authToken')->accessToken;
        $success['user'] = $this->userinfos($user_id);
        ///  $success['profile_image'] = Files::where(['user_id' => $user->id, 'doc_type' => 2])->first('file_path');

        return $this->sendResponse($success, 'User login successfully.');
    }
}
