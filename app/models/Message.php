<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    public function board(){
        return $this->belongsTo('App\Models\Board');
    }
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
