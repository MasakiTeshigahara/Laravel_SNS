<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoritesController extends Controller
{
    public function store(Request $request, Favorite $favorite)
    {
        $user = auth()->user();
        $tweet_id = $request->tweet_id;
        $is_favorite = $favorite->isFavorite($user->id, $tweet_id);

        if(!$is_favorite) {
            $favorite->storeFavorite($user->id, $tweet_id);
            return back();
        }
        return back();
    }

    public function destroy(Favorite $favorite)
    {
        $user_id = $favorite->user_id;
        $tweet_id = $favorite->tweet_id;
        $favorite_id = $favorite->id;
        $is_favorite = $favorite->isFavorite($user_id, $tweet_id);

        if($is_favorite) {
            $favorite->destroyFavorite($favorite_id);
            return back();
        }
        return back();
    }
}