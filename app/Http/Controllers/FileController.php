<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function showAllFiles()
    {
        return response()->json(File::all());
    }

    public function showOneFile($id)
    {
        return response()->json(File::find($id));
    }

    public function create(Request $request)
    {
        $fie = File::create($request->all());

        return response()->json($file, 201);
    }

    public function update($id, Request $request)
    {
        $file = File::findOrFail($id);
        $file->update($request->all());

        return response()->json($file, 200);
    }

    public function delete($id)
    {
        File::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
