<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\User;

class Board extends Model
{
    //
    // 送り元ユーザーとの紐付け
    public function senderUser()
    {
        return $this->hasOne('App\Models\User', 'id', 's_user_id');
    }

    // 送り先ユーザーとの紐付け
    public function recipientUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'r_user_id');
    }

    // DM相手を表示するためにotherUserを用意
    public function otherUser()
    {
        $user_id = Auth::id();
        $other_key = '';
        if ($user_id === $this->s_user_id) {
            $other_key = 'r_user_id';
        } else if ($user_id === $this->r_user_id) {
            $other_key = 's_user_id';
        }
        return $this->hasOne('App\Models\User', 'id', $other_key);
    }

    // メッセージとの紐付け
    public function messages()
    {
        return $this->hasMany('App\Models\Message');
    }
}
