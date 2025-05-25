<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function validate(UserRequest $request, $keys = ["name", "email", "password"])
    {
        $data = $request->only($keys);
        $data['password'] = Hash::make($data['password']);
        return $data;
    }
    public function create(UserRequest $request)
    {
        $data = $this->validate($request);
        $user = User::create($data);
        if ($user) {
            response([
                "messsage" => "Account created successfuly",
                "user" => [
                    "name" => $user->name,
                    "email" => $user->email
                ]
            ], 201);
        } else {
            response([
                "messsage" => "Internal server error !"
            ], 500);
        }
    }
}
