@extends('layouts.app')

@section('content')
<center>
<h1 class="title">Message for ...</h1>
        <h2>{{ $board->otherUser->name }}</h2>
        <hr class="style**">
</center>

<ul class="msgArea list-unstyled"></ul>
        
        <div class="col-lg-6 text-center col-md-8 ml-auto mr-auto">
            <div class="form-group">
                <textarea name="content" class="form-control" placeholder="メッセージを入力...">{{ old('content') }}</textarea>
            </div>

            <input type="hidden" name="bord_id" value="{{ $board->id }}" class="board_id">
            <input type="hidden" name="sender_id" value="{{ $board->senderUser->id }}" class="sender_id">
            <input type="hidden" name="sender_name" value="{{ $board->senderUser->name }}" class="sender_name">
            <input type="hidden" name="sender_icon" value="{{ $board->senderUser->profile_image }}" class="sender_icon">
            <input type="hidden" name="recipient_id" value="{{ $board->recipientUser->id }}" class="recipient_id">
            <input type="hidden" name="recipient_name" value="{{ $board->recipientUser->name }}" class="recipient_name">
            <input type="hidden" name="recipient_icon" value="{{ $board->recipientUser->profile_image }}" class="recipient_icon">
            <input type="hidden" name="messages" value="{{ $messages }}" class="messages">
            <input type="hidden" name="user_id" value="{{ Auth::id() }}" class="user_id">
            <div class="form-group">
                <input type="submit" value="送信" class="btn btn-primary btn-round btn-block btn-lg submit_btn">
        </div>

            



@endsection