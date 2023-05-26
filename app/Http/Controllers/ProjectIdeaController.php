<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectIdea;

class ProjectIdeaController extends Controller
{
    public function showAllProjectIdeas()
    {
        return response()->json(ProjectIdea::all());
    }

    public function showOneProjectIdea($id)
    {
        return response()->json(ProjectIdea::find($id));
    }

    public function create(Request $request)
    {
        $projectIdea = ProjectIdea::create($request->all());

        return response()->json($projectIdea, 201);
    }

    public function update($id, Request $request)
    {
        $projectIdea = ProjectIdea::findOrFail($id);
        $projectIdea->update($request->all());

        return response()->json($projectIdea, 200);
    }

    public function delete($id)
    {
        ProjectIdea::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
