<?php

namespace hiahia\Http\Controllers;

use Illuminate\Http\Request;

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
}
