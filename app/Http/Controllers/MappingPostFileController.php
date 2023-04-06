<?php

namespace App\Http\Controllers;

use App\Models\MappingPostFile;
use Illuminate\Http\Request;

class MappingPostFileController extends Controller
{
    public function showAllPostFiles()
    {
        return response()->json(MappingPostFile::all());
    }

    public function showAllFilesOfPost($id)
    {
        return response()->json(MappingPostFile::find($id));
    }

    public function create(Request $request)
    {
        $fie = MappingPostFile::create($request->all());

        return response()->json($file, 201);
    }

    public function update($id, Request $request)
    {
        $file = MappingPostFile::findOrFail($id);
        $file->update($request->all());

        return response()->json($file, 200);
    }

    public function delete($id)
    {
        MappingPostFile::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
