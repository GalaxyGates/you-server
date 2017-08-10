<?php

namespace hiahia;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function avatars()
    {
        return $this->hasMany('hiahia\Avatar');
    }

    public function contacts()
    {
        return $this->belongsToMany('hiahia\User', 'contacts', 'host_id', 'remote_id')->withTimestamps();
    }

    public function reContacts()
    {
        return $this->belongsToMany('hiahia\User', 'contacts', 'remote_id', 'host_id')->withTimestamps();
    }
}
