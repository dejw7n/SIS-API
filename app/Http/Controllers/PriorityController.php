<?php

namespace App\Http\Controllers;

use App\Models\Priority;
use Illuminate\Http\Request;

class PriorityController extends Controller
{
    public function showAllPriorities()
    {
        return response()->json(Priority::all());
    }

    public function showOnePriority($id)
    {
        return response()->json(Priority::find($id));
    }

    public function create(Request $request)
    {
        $priority = Priority::create($request->all());

        return response()->json($priority, 201);
    }

    public function update($id, Request $request)
    {
        $priority = Priority::findOrFail($id);
        $priority->update($request->all());

        return response()->json($priority, 200);
    }

    public function delete($id)
    {
        Priority::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
