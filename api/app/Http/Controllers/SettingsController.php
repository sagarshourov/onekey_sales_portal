<?php

namespace App\Http\Controllers;

use App\Models\CancelReason;
use App\Models\Package;
use App\Models\Results;
use App\Models\Sections;
use App\Models\Status;
use App\Models\Priority;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends BaseController
{


    private function all_data()
    {
        $re['sections'] = Sections::get(['id', 'title', 'theme', 'start_date', 'end_date']);

        $re['cancel_reason'] = CancelReason::get(['id', 'title']);
        $re['packages'] = Package::get(['id', 'title']);
        $re['status'] = Status::get(['id', 'title']);
        $re['results'] = Results::get(['id', 'title']);
        $re['priorities'] = Priority::get(['id', 'title']);

        return $re;
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //




        return $this->sendResponse($this->all_data(), 'All Tables Retrieve successfully.');
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
        $table = $input['table'];
        unset($input['table']);
        if (isset($input['id'])) {
            $id = $input['id'];
            unset($input['id']);
            DB::table($table)
                ->where('id',  $id)
                ->update($input);
        } else {
            DB::table($table)->insert($input);
        }




        return $this->sendResponse($this->all_data(), 'All Tables Retrieve successfully.');
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
    public function destroy($table, $id)
    {
        //
        DB::table($table)->where('id', '=', $id)->delete();
        return $this->sendResponse($this->all_data(), "Deleted Successfully");
    }
}
