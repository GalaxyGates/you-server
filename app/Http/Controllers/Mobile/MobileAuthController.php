<?php
/**
 * Created by PhpStorm.
 * User: 34883
 * Date: 2017-07-26
 * Time: 12:11
 */

namespace hiahia\Http\Controllers\Mobile;

use hiahia\User;
use Illuminate\Http\Request;
use hiahia\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Log;
use Webpatser\Uuid\Uuid;

class MobileAuthController extends Controller
{
    protected $TOKEN_EXPIRE_TIME = 1000 * 3600 * 30;

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

            $uuid = (string)Uuid::generate(5, $username, Uuid::NS_DNS);
            Cache::tags('mobile_token')->put($username, $uuid, $this->TOKEN_EXPIRE_TIME);
            return response()->json(['status' => 1, 'error_code' => 0, 'token' => $uuid]);
        } else {
            return response()->json(['status' => 0]);
        }
    }

    protected function getStatus(Request $request)
    {

    }

    protected function connect(Request $request)
    {

    }
}