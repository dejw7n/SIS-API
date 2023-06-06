<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserToken;
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
        $password = $request->input('password');
        $hashedPassword = Hash::make($password);
        $user->password = $hashedPassword;
        $user = User::create($request->all());
        $user->save();

        return response()->json($user, 201);
    }

    public function update($id, Request $request)
    {
        $user = User::findOrFail($id);
        $reqUser = $request->all();
        if ($reqUser->password) {
            $password = $reqUser->password;
            $hashedPassword = Hash::make($password);
            $reqUser->password = $hashedPassword;
        }
        $user->update($request->all());
        //delete all sessions of user
        UserToken::where('user_id', $id)->delete();

        return response()->json($user, 200);
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
