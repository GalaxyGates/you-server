<?php
/**
 * Created by PhpStorm.
 * User: 34883
 * Date: 2017-07-26
 * Time: 12:11
 */

namespace hiahia\Http\Controllers\API;

use hiahia\User;
use Illuminate\Http\Request;
use hiahia\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Log;
use Webpatser\Uuid\Uuid;
class AuthController extends Controller
{
    protected $TOKEN_EXPIRE_TIME = 1000 * 3600 * 30;

    public function __construct()
    {
    }

    protected function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        Log::info("login attemping:username:$username password:$password");
        if (Auth::attempt([
            'email' => $username,
            'password' => $password
        ])
        ) {

            $uuid = (string)Uuid::generate(5, $username + time(), Uuid::NS_DNS);
            Cache::tags('mobile_token')->put($username, $uuid, $this->TOKEN_EXPIRE_TIME);
            return response()->json(['status' => 1, 'error_code' => 0, 'token' => $uuid]);
        } else {
            return response()->json(['status' => 0]);
        }
    }

    protected function loginWithToken(Request $request)
    {

        Log::info("login attemping with token");
        if (Auth::check()) {
            return response()->json(['status' => 1, 'error_code' => 0]);
        }
        $username = $request->input('username');
        $token = $request->input('token');
        Log::info("login attemping:username:$username token:$token");
        $ourToken = Cache::tags('mobile_token')->get($username, '');
        if ($ourToken == '') {
            return response()->json(['status' => 0, 'error_code' => 1]);
        } else if ($ourToken != $token) {
            return response()->json(['status' => 0, 'error_code' => 2]);
        } else {
            Auth::login(User::where('email', '=', $username)->first(), true);
            return response()->json(['status' => 1, 'error_code' => 0]);
        }
    }
    protected function getStatus(Request $request)
    {
        $username = $request->input('username');
        $token = $request->input('token');
        $ourToken = Cache::tags('mobile_token')->get($username, '');
        $token_status = ($ourToken == '') ? false : $ourToken == $token;
        $http_status = Auth::check();
        return response()->json(['http_status' => $http_status, 'token_status' => $token_status]);
    }
    protected function connect(Request $request)
    {

    }
}