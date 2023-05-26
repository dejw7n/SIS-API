<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Idea;

class IdeaController extends Controller
{
    public function showAllIdeas()
    {
        return response()->json(Idea::all());
    }

    public function showOneProjectIdea($id)
    {
        return response()->json(Idea::find($id));
    }

    public function create(Request $request)
    {
        $query = Idea::create($request->all());

        return response()->json($query, 201);
    }

    public function update($id, Request $request)
    {
        $query = Idea::findOrFail($id);
        $query->update($request->all());

        return response()->json($query, 200);
    }

    public function delete($id)
    {
        Idea::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
