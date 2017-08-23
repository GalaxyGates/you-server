<?php

namespace hiahia\Http\Controllers\API;

use hiahia\Http\Controllers\Controller;
use hiahia\User;
use hiahia\Contact;
use hiahia\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Swoole\Exception;
use Webpatser\Uuid\Uuid;

use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function createContactQRToken(Request $request)
    {
        $user = $request->user();
        $uuid = (string)Uuid::generate(5, $user->name + time()+'.qr_salt', Uuid::NS_DNS);
        Cache::tags('contact_qr_token')->put($uuid, $user->id,5);/**/
        return response()->json(['status'=>1,'qr_token'=>$uuid]);
    }

    public function addByQRToken(Request $request)
    {
        $user = $request->user();
        $token = $request->input('token');
        if ($token == null)
            //空的token
            return response()->json(['status' => 0, 'error_code' => 1]);
        $remote_id = Cache::tags('contact_qr_token')->get($token, -1);
        if ($remote_id == -1)
            //二维码失效
            return response()->json(['status' => 0, 'error_code' => 2]);
        try {
            if($remote_id == $user->id)
                //给自己通话？
                return response()->json(['status'=>0,'error_code'=>3]);
            $remote = User::findOrFail($remote_id);
            Contact::add($user, $remote);
            if($user->contacts()->get()->contains($remote))
                //已经在好友列表里
                return response()->json(['status'=>0,'error_code'=>4]);
            //返回成功
            return response()->json(['status' => 1]);
        } catch (ModelNotFoundException $e) {
            Log::info('ContactController::addByQRToken:' . $e->getMessage());
            //用户不存在
            return response()->json(['status' => 0, 'error_code' => 5]);
        }

    }
}
