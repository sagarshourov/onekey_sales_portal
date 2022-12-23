<?php

namespace App\Http\Controllers;

use App\Models\Calls;
use Illuminate\Http\Request;

class CallsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private function get_calls()
    {
        return Calls::with(['results','priority','status','package','cancel_reason'])->get();
    }



    public function index()
    {
        //

        return $this->sendResponse($this->get_calls(), 'Retrive successfully.');
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


        if (isset($input['id'])) {
            $id =  $input['id'];
            $data = Calls::updateOrCreate(
                ['id' =>  (int) $id],
                $input

            );
            return $this->sendResponse($this->get_calls(), 'Update successfully.');
        } else {
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
            Calls::where('id', (int)  $id)
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
}
