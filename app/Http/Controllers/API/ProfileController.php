<?php

namespace hiahia\Http\Controllers\API;


use Illuminate\Http\Request;
use hiahia\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;


use Webpatser\Uuid\Uuid;

class ProfileController extends Controller
{
    //
    public function __construct()
    {
    }

    public function getHomeProfile(Request $request)
    {
        return $request->user()->getHomeProfile();
    }

    public function updateProfile(Request $request)
    {
        $name = $request->input('name','');
        $motto = $request->input('motto','');
        $mobile = $request->input('mobile','');
        $gender = $request->input('gender','');
        $request->user()->updateProfile($name, $motto, $mobile,$gender);
        return response()->json(['status' => 1]);
    }

}
