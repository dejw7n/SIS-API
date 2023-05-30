<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\IssuePriority;
use App\Models\IssueStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IssueController extends Controller
{
    public function showAllIssues()
    {
        $issues = DB::table('issues')
            ->orderBy('created_at', 'desc')
            ->get();
        foreach ($issues as $issue) {
            $issue->author = User::find($issue->author_id);
        }
        return response()->json($issues);
    }

    public function showOneIssue($id)
    {
        return response()->json(Issue::find($id));
    }

    public function create(Request $request)
    {
        $statusId = IssueStatus::where('name', 'pending')
            ->pluck('id')
            ->first();
        $request->merge(['status_id' => $statusId]);
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
        return response('', 200);
    }

    //priorities
    public function showAllPriorities()
    {
        return response()->json(IssuePriority::all());
    }

    //statuses
    public function showAllStatuses()
    {
        return response()->json(IssueStatus::all());
    }
}
