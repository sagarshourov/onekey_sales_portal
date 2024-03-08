<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AssignEmployee;


use App\Exports\CallExport;
use App\Models\Calls;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class UserController extends BaseController
{

    public function export()
    {
        $calls_count = Calls::count();
        $num = $calls_count / 10000;
        $filePaths = array();
        for ($a = 0; $a <= $num; $a++) {
            $file_name = 'users' . time() . $a . '.xlsx';
            $filePaths[] =  $file_name;
            Excel::store(new CallExport(0, '', 0, $a + 1), $file_name);
        }
        $currentDate = Carbon::now()->format('Y-m-d');
        $zipFileName =  $currentDate . '_files.zip';
        $zip = new ZipArchive();
        if ($zip->open(public_path($zipFileName), ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            foreach ($filePaths as $filePath) {
                $file = Storage::path($filePath);
                $fileName = basename($file);
                $zip->addFile($file, $fileName);
            }
            $zip->close();
        }
        foreach ($filePaths as $filePath) {
            File::delete(storage_path('app/' . $filePath));
        }
        return response()->download(public_path($zipFileName))->deleteFileAfterSend(true);
    }

    public function single_export($result, $title, $user_id)
    {
        // return Excel::download(new CallExport($result, $title, $user_id, 1), 'users.xlsx');

        $calls_count = Calls::count();
        $num = $calls_count / 10000;
        $filePaths = array();
        for ($a = 0; $a <= $num; $a++) {
            $file_name = 'users' . time() . $a . '.xlsx';
            $filePaths[] =  $file_name;
            Excel::store(new CallExport($result, $title, $user_id, $a + 1), $file_name);
        }
        $currentDate = Carbon::now()->format('Y-m-d');
        $zipFileName =  $currentDate . '_files.zip';
        $zip = new ZipArchive();
        if ($zip->open(public_path($zipFileName), ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            foreach ($filePaths as $filePath) {
                $file = Storage::path($filePath);
                $fileName = basename($file);
                $zip->addFile($file, $fileName);
            }
            $zip->close();
        }
        foreach ($filePaths as $filePath) {
            File::delete(storage_path('app/' . $filePath));
        }
        return response()->download(public_path($zipFileName))->deleteFileAfterSend(true);
    }

    public function users_sort(Request $request)
    {
        $all = $request->all();

        foreach ($all['sort'] as $key => $val) {
            User::where('id', $val)
                ->update(['sort' => $key + 1]);
        }
        return $this->sendResponse([], 'Users short successfully.');
    }

    public function assign_employee(Request $request)
    {
        $input = $request->all();

        if (count($input['data'])  > 0) {

            foreach ($input['data'] as $user_id) {
                AssignEmployee::create([
                    'admin_id' => $input['user_id'],
                    'user_id' => $user_id,
                ]);
            }
        } else {
            return $this->sendError($request->all(), 'Something wrong.');
        }
        return $this->sendResponse($request->all(), 'Admin retrieved successfully.');
    }


    public function get_assign_employee($id)
    {
        // $users =  AdminUsers::with(['user'])->where('admin_id', 1)->orderByDesc('id')->get();

        $users =  AssignEmployee::with('users')->where('admin_id', $id)->orderByDesc('id')->get();
        return $this->sendResponse($users, 'Users retrieved successfully.');
    }


    public function del_assign_employee(Request $request)
    {
        $input = $request->all();


        AssignEmployee::where(['admin_id' => $input['admin_id'], 'user_id' => $input['user_id']])->forceDelete();

        return $this->sendResponse(['success'], 'Users retrieved successfully.');
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
        $user = Auth::user();

        if ($user->is_admin == 4) {

            $emp = AssignEmployee::where('admin_id', $user->id)->pluck('user_id')->toArray();;
            $emp[] = $user->id;
            $users = User::WhereIn('id', $emp)->with(['profile'])->orderBy('sort', 'ASC')->get();
        } else {
            $users = User::with(['profile'])->orderBy('sort', 'ASC')->get();
        }


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
