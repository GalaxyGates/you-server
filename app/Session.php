<?php

namespace hiahia;

use Illuminate\Database\Eloquent\Model;
use hiahia\User;
use Illuminate\Support\Collection;

class Session extends Model
{
    //
    static public function add($users)
    {
        $cUsers = collect($users);
        $ids = $cUsers->pluck('id');
        $json = json_encode($ids);
        $session = new Session();
        $session->users = $json;
        $session->save();
        return $session;
    }

    static public function addByID($ids)
    {
        $json = json_encode($ids);
        $session = new Session();
        $session->users = $json;
        $session->save();
        return $session;
    }

    /**
     * @return mixed
     */
    public function findUserByID()
    {
        $json = $this->users;
        $ids = json_decode($json, true);
        return User::find($ids);
    }
}
