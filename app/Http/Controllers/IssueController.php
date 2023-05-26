<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    public function showAllIssues()
    {
        return response()->json(Issue::all());
    }

    public function showOneIssue($id)
    {
        return response()->jsonIssue::find($id));
    }

    public function create(Request $request)
    {
        $query = Issue::create($request->all());

        return response()->json($query, 201);
    }

    public function update($id, Request $request)
    {
        $query = Issue::findOrFail($id);
        $query->update($request->all());

        return response()->json($query, 200);
    }

    public function delete($id)
    {
        Issue::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
