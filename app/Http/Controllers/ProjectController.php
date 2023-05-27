<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function showAllProjects()
    {
        $projects = DB::table('projects')
            ->orderBy('created_at', 'desc')
            ->get();
        foreach ($projects as $project) {
            $project->author = User::find($project->author_id);
        }
        return response()->json($projects);
    }

    public function showOneProject($id)
    {
        return response()->json(Project::find($id));
    }

    public function create(Request $request)
    {
        $project = Project::create($request->all());

        return response()->json($project, 201);
    }

    public function update($id, Request $request)
    {
        $project = Project::findOrFail($id);
        $project->update($request->all());

        return response()->json($project, 200);
    }

    public function delete($id)
    {
        Project::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
