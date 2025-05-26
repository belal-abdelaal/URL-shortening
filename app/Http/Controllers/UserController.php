<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartialUserRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\password;

class UserController extends Controller
{
    public function validate(UserRequest|PartialUserRequest $request)
    {
        return $request->validated();
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
    public function login(PartialUserRequest $request)
    {
        $data = $this->validate($request);
        $user = User::where("email", $data["email"])->first();
        if ($user) {
            $token = $user->createToken($user->name);
            return response(["token" => $token->plainTextToken]);
        }
    }
}
