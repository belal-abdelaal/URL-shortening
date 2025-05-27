<?php

namespace App\Http\Controllers;

use App\Http\Requests\URLRequest;
use App\Http\Resources\UrlCollection;
use App\Http\Resources\UrlResource;
use App\Models\Url;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

use function PHPSTORM_META\type;

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
    public function get(Request $request, $id = null)
    {
        $userId = $this->parseUserToken($request->header("token"));
        $url_s = $id ?
            Url::where("user_id", $userId)->where("id", $id)->first() :
            Url::where("user_id", $userId)->get();

        if (!$url_s)
            return response([
                "message" => "URL not found"
            ], 404);

        if (!isset($url_s["id"]))
            return response(new UrlCollection($url_s));
        else
            return response(new UrlResource($url_s));
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
    public function delete(Request $request, $id)
    {
        $userId = $this->parseUserToken($request->header("token"));
        $url = Url::where("user_id", $userId)->where("id", $id)->first();
        if (!$url)
            return response([
                "message" => "URL not found"
            ], 404);

        try {
            $url->delete();
        } catch (\Throwable $th) {
            return response([
                "message" => $th->getMessage()
            ], 500);
        }
        return response([
            "message" => "URL deleted successfuly"
        ]);
    }
}
