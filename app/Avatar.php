<?php

namespace hiahia;

use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    //

    public function user()
    {
        return $this->belongsTo('hiahia\User');
    }
}
