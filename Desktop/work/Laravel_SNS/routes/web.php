<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//ログアウト
Route::get('/logout','Auth\LoginController@__construct');

//ログイン状態
Route::group(['middleware' => 'auth'], function() {

    //ログアウト
    Route::get('/logout','Auth\LoginController@__construct');

    //ユーザ関連　一覧、詳細、編集、更新ができる設定
    Route::resource('users', 'UsersController', ['only' => ['index', 'show', 'edit', 'update']]);

    // フォロー/フォロー解除を追加
    Route::post('users/{user}/follow', 'UsersController@follow')->name('follow');
    Route::delete('users/{user}/unfollow', 'UsersController@unfollow')->name('unfollow');

        // ツイート関連
    Route::resource('tweets', 'TweetsController', ['only' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']]);

      // コメント関連
      Route::resource('comments', 'CommentsController', ['only' => ['store']]);

      // いいね関連
    Route::resource('favorites', 'FavoritesController', ['only' => ['store', 'destroy']]);

});
