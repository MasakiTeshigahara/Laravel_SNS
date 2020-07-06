<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\message;
use App\Models\board;
use Illuminate\Support\Facades\Auth;


class BoardsController extends Controller
{
    public function index()
    {
//ログインユーザーIDを取得し、そのユーザーが送った、または受け取ったboradを取得。
        $current_user_id = Auth::user()->id;
        $boards = Board::where('s_user_id', $current_user_id)->orwhere('r_user_id', $current_user_id)->get();
        return view('mypage', ['boards' => $boards]);
    }

    public function show($id)
    {
//ボードモデルおよびメッセージモデルから、クリックしたboard_idの情報を取得する。
        $messages = Message::where('board_id', $id)->get();
        $board = Board::find($id);

        return view('messages', ['messages' => $messages, 'board' => $board]);
    }

    public function store(Request $request)
    {
        $board = new Board;
        $board->s_user_id = $request->s_user_id;
        $board->r_user_id = $request->r_user_id;
        $board->save();

//boards一覧表示に戻る
        return redirect()->action(
            'BoardsController@index',
            ['board' => $board]
        );
    }

}
