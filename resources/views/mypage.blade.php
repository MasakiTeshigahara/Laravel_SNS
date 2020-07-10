@extends('layouts.app')

@section('content')
@if (count($boards) > 0)
<div class="container">
    <div class="row">
        @foreach ($boards as $board)
        <div class="col-xs-6 ">
            <a href="{{ route('messages',$board->id) }}">
                <div class="d-flex flex-column" style="height: 50px; width: 500px;">
                    <div class="flex-fill border">
                    @if ($is_following)
                        <h3 class="title">{{ $board->otherUser->name }}</h3>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection