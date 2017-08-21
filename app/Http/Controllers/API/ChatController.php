<?php

namespace hiahia\Http\Controllers\API;


use Illuminate\Http\Request;
use hiahia\Http\Controllers\Controller;
use hiahia\User as User;
use hiahia\Notifications\message as Message;

class ChatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function chat()
    {
        return view('chat.chat');
    }

    public function sendMessage(Request $request)
    {
        $remote = User::where("email", '=', ($request->input("remote")))->first();

        if ($remote == null) {
            return [
                'rtv' => 0,
                'msg' => '用户不存在',
                'remote' => ($request->input("remote"))
            ];
        }

        $sender = $request->user()->email;
        $text = $request->input('text');
        $time = time();

        $remote->notify(new Message($time, $text, $sender));

        return [
            'rtv' => 1
        ];
    }

    //当客户端读取数据之后
    public function readMessage(Request $request)
    {
        $notifications = $request->user()->unreadNotifications;
        $notifications->markAsRead();
        return $notifications->reverse()->pluck('data');
    }

    public function readName(Request $request)
    {
        $user = User::where('email', '=', $request->input('email'))->first();
        if ($user == null) {
            return '';
        }
        $name = $user->name;
        return $name;
    }

    public function readStatus(Request $request)
    {

    }

    public function testNotifications(Request $request)
    {
        /*        $user   = $request->user();
                $sender = $request->user()->email;
                $time   = time();
                for($i=0;$i<1000000000;$i++)
                {
                    $user->notify(new Message($time,"test message($i)",$sender));
                }
        */
    }

}
