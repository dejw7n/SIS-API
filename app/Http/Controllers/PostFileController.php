<?php

namespace App\Http\Controllers;

use App\Models\PostFile;
use Illuminate\Http\Request;

class PostFileController extends Controller
{
    public function showAllPostFiles()
    {
        return response()->json(PostFile::all());
    }

    public function showAllFilesOfPost($id)
    {
        return response()->json(PostFile::find($id));
    }

    public function create(Request $request)
    {
        $fie = PostFile::create($request->all());

        return response()->json($file, 201);
    }

    public function update($id, Request $request)
    {
        $file = PostFile::findOrFail($id);
        $file->update($request->all());

        return response()->json($file, 200);
    }

    public function delete($id)
    {
        PostFile::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
