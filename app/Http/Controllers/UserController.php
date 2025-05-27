<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartialUserRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\password;

class UserController extends Controller
{
    public function validate(UserRequest|UserLoginRequest|PartialUserRequest $request, $keys = ["name", "email", "password"])
    {
        return $request->only($keys);
    }
    public function create(UserRequest $request)
    {
        $data = $this->validate($request);
        $user = User::create($data);
        if ($user) {
            return response([
                "messsage" => "Account created successfuly",
                "user" => [
                    "name" => $user->name,
                    "email" => $user->email
                ]
            ], 201);
        } else {
            return response([
                "messsage" => "Internal server error !"
            ], 500);
        }
    }
    public function login(UserLoginRequest $request)
    {
        $data = $this->validate($request, ["email", "password"]);
        $user = User::where("email", $data["email"])->first();
        if (!$user)
            return response(["message" => "Internal server error !"]);

        if (!Hash::check($data["password"], $user->password))
            return response(["message" => "The password is incorrect"], 400);

        $token = $user->createToken($user->name);
        return response(["token" => $token->plainTextToken]);
    }
}
