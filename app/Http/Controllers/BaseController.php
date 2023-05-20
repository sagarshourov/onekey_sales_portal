<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller as Controller;
use App\Models\AdminUsers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Request;
//use Illuminate\Http\Request;
use App\Models\Activity;

class BaseController extends Controller
{

    public  function addToLog($subject)

    {


        $user = Auth::user();

        if ($user && $user->is_admin == 3) {

            $log = [];

            $log['subject'] = $subject;

            $log['url'] = Request::fullUrl();

            $log['method'] = Request::method();

            $log['ip'] = Request::ip();

            $log['agent'] = Request::header('user-agent');

            $log['user_id'] =    $user->id;

            Activity::create($log);
        }
    }


    /**

     * success response method.

     *

     * @return \Illuminate\Http\Response

     */
    public function sendResponse($result, $message)
    {

        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];

        $this->addToLog($message);


        return response()->json($response, 200);
    }

    /**

     * return error response.

     *

     * @return \Illuminate\Http\Response

     */
    public function sendError($error, $errorMessages = [], $code = 403)
    {

        $response = [
            'success' => false,
            'message' => $error,
        ];


        if (!empty($errorMessages)) {

            $response['data'] = $errorMessages;
        }

        //  $this->addToLog($errorMessages);

        return response()->json($response, $code);
    }



    public function logActivityLists($id)

    {

        $data =  Activity::where('user_id', $id)->orderBy('id', 'DESC')->get();


        $response = [
            'success' => true,
            'data' => $data,
            'message' => "Activity log retrieved! ",
        ];




        return response()->json($response, 200);
    }
}
