<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function showAllUsers()
    {
        return response()->json(User::all());
    }

    public function showOneUser($id)
    {
        return response()->json(User::find($id));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'lname' => 'required|string',
            'phone' => 'required|string',
            'role_id' => 'required|exists:roles,id',
            'center_id' => 'required|exists:centers,id',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
        ]);
        $user = new User();
        $user->name = $request->input('name');
        $user->lname = $request->input('lname');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        $user->role_id = $request->input('role_id');
        $user->center_id = $request->input('center_id');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json($user, 201);
    }

    public function update($id, Request $request)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());

        return response()->json($user, 200);
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
