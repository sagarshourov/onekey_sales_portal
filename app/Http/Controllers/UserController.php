<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Exports\CallExport;

use Maatwebsite\Excel\Facades\Excel;

class UserController extends BaseController
{

    public function export()
    {
        return Excel::download(new CallExport(0, '', 0), 'users.xlsx');
    }

    public function single_export($result, $title, $user_id)
    {
        return Excel::download(new CallExport($result, $title, $user_id), 'users.xlsx');
    }

    public function users_sort(Request $request)
    {
        $all = $request->all();

        foreach ($all['sort'] as $key => $val) {
            User::where('id', $val)
                ->update(['sort' => $key+1]);
        }
        return $this->sendResponse([], 'Users short successfully.');
    }








    // public function import() 
    // {
    //     Excel::import(new CallImport, storage_path('users.xlsx'));
    //     //echo storage_path('users.xlsx');
    // }







    // public function export() 
    // {
    //     $spreadsheet = new Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();
    //     $sheet->setCellValue('A1', 'Hello World !');

    //     // $writer = new Xlsx($spreadsheet);
    //     // $writer->save('hello world.xlsx');

    //     $writer = new Xlsx($spreadsheet);
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment; filename="'. urlencode('hello world.xlsx').'"');
    //     $writer->save('php://output');
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private function users()
    {
        $users = User::with(['profile'])->orderBy('sort', 'ASC')->get();
        return $users;
    }


    public function index()
    {
        //
        return $this->sendResponse($this->users(), 'User retrieve successfully.');
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
        return $this->sendResponse($this->users(), 'New user Add successfully.');
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
        return $this->sendResponse($this->users(), 'User Info Updated successfully.');
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

        return $this->sendResponse($this->users(), 'User deleted successfully.');
    }
}
