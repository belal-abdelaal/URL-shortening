<?php

namespace App\Http\Controllers;

use App\Http\Requests\URLRequest;
use App\Models\Url;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class URLController extends Controller
{
    public function validate(URLRequest $request)
    {
        return $request->only("url")["url"];
    }
    public function parseUserToken($token)
    {
        $token = PersonalAccessToken::findToken($token);
        return $token->tokenable_id;
    }
    public function create(URLRequest $request)
    {
        $url = $this->validate($request);
        $userId = $this->parseUserToken($request->header("token"));
        try {
            $short_url = Url::create([
                "user_id" => $userId,
                "url" => $url,
                "short_uri" => uniqid()
            ]);
        } catch (\Exception $th) {
            return response([
                "message" => $th->getMessage()
            ], 500);
        }
        return response([
            "message" => "URL created successfuly",
            "short_url" => "http://127.0.0.1:8000/" . $short_url->short_uri
        ], 201);
    }
    public function redirect($short_uri)
    {
        $url = Url::where("short_uri", $short_uri)->first();
        if (!$url)
            return response([
                "message" => "URL not found"
            ], 404);

        $url->accessCount++;
        $url->save();
        return redirect($url->url);
    }
}
