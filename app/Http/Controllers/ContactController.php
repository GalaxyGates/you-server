<?php

namespace hiahia\Http\Controllers;

use hiahia\User;
use hiahia\Contact;
use hiahia\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Swoole\Exception;

/**
 * Class ContactController
 * @package hiahia\Http\Controllers
 */
class ContactController extends Controller
{
    //
    /**
     * ContactController constructor.
     */
    public function __construct()
    {
    }

    public function getList(Request $request)
    {
        try {
            $contacts = Auth::user()->contacts()->with(['avatars' => function ($query) {
                $query->orderBy('id', 'desc')->first();
            }]);
            return $contacts->get();
        } catch (Exception $e) {
            return response()->json(['status' => 2, 'error_code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }

    public function test(Request $request)
    {
        Session::addByID([0, 1, 2, 3, 4, 5, 6]);
        return Session::all();
    }

    public function add(Request $request)
    {
        try {
            $remote_email = $request->input('remote_email');
            $remote = User::getByEmail($remote_email);
            if ($remote == null) {
                return response()->json(['status' => 0, 'error_code' => 1]);
            } else {
                $session = Session::add($request->user(), $remote);
                Contact::add($request->user(), $remote, $session);
                return response()->json(['status' => 1, 'error_code' => 0]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 2, 'error_code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        try {
            $remote_email = $request->input('remote_email');
            $remote = $request->user()->getContactByEmail($remote_email);
            if ($remote == null) {
                return response()->json(['status' => 0, 'error_code' => 1]);
            } else {
                Contact::del($request->user(), $remote);
                return response()->json(['status' => 1, 'error_code' => 0]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 2, 'error_code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }
}
