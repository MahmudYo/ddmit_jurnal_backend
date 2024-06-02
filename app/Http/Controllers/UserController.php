<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function passwordWithUsername(string $username)
    {
        return response()->json(User::where('username', $username)->first());
    }
    public function update(string $id, Request $request)
    {
        $user = User::find($id);
        if ($user) {
            $validate = $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);
            if ($validate) {
                $user->password = Hash::make($request['password']);
                $user->username = $request['username'];
                $user->update();
                return response()->json($user);
            }
        } else {
            return response()->json('not user', 404);
        }
    }
}
