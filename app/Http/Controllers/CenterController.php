<?php

namespace App\Http\Controllers;

use App\Models\Center;
use Illuminate\Http\Request;

class CenterController extends Controller
{
    public function showAllCenters()
    {
        return response()->json(Center::all());
    }

    public function showOneCenter($id)
    {
        return response()->json(Center::find($id));
    }

    public function create(Request $request)
    {
        $center = Center::create($request->all());

        return response()->json($center, 201);
    }

    public function update($id, Request $request)
    {
        $center = Center::findOrFail($id);
        $center->update($request->all());

        return response()->json($center, 200);
    }

    public function delete($id)
    {
        Center::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
