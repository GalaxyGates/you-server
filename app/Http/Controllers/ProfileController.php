<?php

namespace hiahia\Http\Controllers;

use Illuminate\Http\Request;

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
        $name = $request->input('name');
        $email = $request->input('email');
        $motto = $request->input('motto');
        $mobile = $request->input('mobile');
        $request->user()->updateProfile($name, $email, $motto, $mobile);
    }

}
