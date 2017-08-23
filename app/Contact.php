<?php

namespace hiahia;

use Illuminate\Database\Eloquent\Model;
use hiahia\User;
use hiahia\Session;


class Contact extends Model
{
    //

    static public function add(User $host, User $remote)
    {
        $session = Session::add([$host, $remote]);
        return [Contact::add_single($host, $remote, $session), Contact::add_single($remote, $host, $session)];
    }

    static public function add_single(User $host, User $remote, Session $session)
    {
        $contact = new Contact();
        $contact->host_id = $host->id;
        $contact->remote_id = $remote->id;
        $contact->session_id = $session->id;
        $contact->save();
        return $contact;
    }

    static public function del(User $host, User $remote)
    {
        Contact::del_single($host, $remote);
        Contact::del_single($remote, $host)->delete();
    }

    static public function del_single(User $host, User $remote)
    {
        $contact = Contact::where('host_id', $host->id)->where('remote_id', $remote->id);
        $session = $contact->session();
        $contact->delete();
        return $session;
    }

    public function host()
    {
        return $this->belongsTo('hiaia\User', 'host_id');
    }

    public function remote()
    {
        return $this->belongsTo('hiahia\User', 'remote_id');
    }
    public function session()
    {
        return $this->hasOne('hiahia\Session');
    }
}
