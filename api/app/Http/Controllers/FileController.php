<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Files;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends BaseController
{
    //

    public function getFile($folder,  $filename)
    {

        $file = Storage::get($folder . '/' . $filename);
        return $file;
    }
    public function file_upload(Request $request)
    {
        $user = Auth::user();
        $input = $request->all();
        $path = $request->file('file')->store('files');
        $user_id =  $user->id;
        if ($input['type'] == 2) {
            $file = Files::updateOrCreate([
                'user_id'   =>  $user_id,
                'doc_type' => $input['type']
            ], [
                'file_path' =>  $path,
                'user_id'   =>  $user_id,
                'doc_type' => $input['type']

            ]);
            return $this->sendResponse($path, 'Profile picture uploaded successfully.');
        } else {
            $file = new Files;
            $file->title = $input['title'];
            $file->file_path = $path;
            $file->doc_type = (int) $input['type'];
            $file->user_id = $user_id;
            $file->save();
        }
        return $this->sendResponse($this->userfiles(), 'File uploaded successfully.');
    }

    public function record_upload(Request $request)
    {

        $path = $request->file('file')->store('recorded');
        $input = $request->all();

        $file = new Files;
        $file->title = $input['id'];
        $file->file_path = $path;
        $file->doc_type = 6;
        $file->user_id = $input['user_id'];
        $file->save();
    }
    public function record_history($id)
    {

        $files = Files::where('title', $id)->get();
        return $this->sendResponse($files, 'WhatsApp Record retrieve successfully.');
    }
}
