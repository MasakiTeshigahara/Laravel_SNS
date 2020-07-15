<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tweet;
use App\Models\Follower;
use App\Models\Board;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //ユーザを取得するgetAllUsers()というメソッドにログインしているユーザIDを引数で渡しています。
    //Modelから返ってきた結果をViewに返します。
    public function index(User $user)
    {
        $all_users = $user->getAllUsers(auth()->user()->id);

        return view('users.index', [
            'all_users'  => $all_users
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'screen_name'   => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'name'          => ['required', 'string', 'max:255'],
            'profile_text'  => ['required', 'string', 'max:100'],
            'profile_image' => ['file', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'email'         => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)]
        ]);
        $validator->validate();
        $user->updateProfile($data);

        return redirect('users/' . $user->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // フォロー
    public function follow(User $user)
    {
        $follower = auth()->user();
        // フォローしているか
        $is_following = $follower->isFollowing($user->id);
        if (!$is_following) {
            // フォローしていなければフォローする
            $follower->follow($user->id);
            return back();
        }
    }



    // フォロー解除
    public function unfollow(User $user)
    {
        $follower = auth()->user();
        // フォローしているか
        $is_following = $follower->isFollowing($user->id);
        if ($is_following) {
            // フォローしていればフォローを解除する
            $follower->unfollow($user->id);
            return back();
        }
    }

    private function searchBoard($login_user, $user){
        $boards = $this->getBoard($login_user->id, $user->id);
        if(count($boards) == 0){
            $boards = $this->getBoard($user->id, $login_user->id);
        }
        if (count($boards) == 0) {
            $board = new Board;
            $board->s_user_id = $login_user->id;
            $board->r_user_id = $user->id;
            $board->save();
            
            $boards = $this->getBoard($login_user->id, $user->id);
        } 
        return $boards[0];
    }
    
    private function getBoard($s_user_id, $r_user_id){
        return Board::where([
                ['s_user_id', $s_user_id],
                ['r_user_id', $r_user_id]
                ])->get();
    }



    public function show(User $user, Tweet $tweet, Follower $follower)
    {
        $login_user = auth()->user();
        $is_following = $login_user->isFollowing($user->id);
        $is_followed = $login_user->isFollowed($user->id);
        $timelines = $tweet->getUserTimeLine($user->id);
        $tweet_count = $tweet->getTweetCount($user->id);
        $follow_count = $follower->getFollowCount($user->id);
        $follower_count = $follower->getFollowerCount($user->id);


        $board = '';
        if($login_user->id != $user->id){
            $board = $this->searchBoard($login_user, $user);
        }


        return view('users.show', [
            'user'           => $user,
            'is_following'   => $is_following,
            'is_followed'    => $is_followed,
            'timelines'      => $timelines,
            'tweet_count'    => $tweet_count,
            'follow_count'   => $follow_count,
            'follower_count' => $follower_count,
            'board'  => $board
        ]);
    }

    public function getLogout()
    {
        $user = Auth::user();

        Auth::logout(); // ログアウト、update処理が行われる。
        if ($user->RememberToken) {
            $user->rememberToken()->delete(); // Itemが削除される。
            $user->delete(); // ユーザが削除される。
        } else
            return redirect("/");
    }

    public function following(Follower $follower)
    {
        $user = auth()->user();
        $user_ids_arr = [];
        // followersテーブルからフォローしているユーザーIDデータを取得する
        $ids = $follower->followingIds($user->id);
        foreach ($ids as $id) {
            // 取得したユーザーIDデータからIDのみ抜き出し、配列に格納する
            array_push($user_ids_arr, $id->followed_id);
        }
        //$user_ids_arrが空欄かどうか判定する
        if (empty($user_ids_arr)) {
            echo '<center>フォローしているアカウントはいません</center>';
        } else {
            // フォローユーザーid配列を引数に、ユーザー情報を取得する
            $all_users = $user->getUsers($user_ids_arr);
            //users/index.bladeで表示処理
            return view('users.index', [
                'all_users'  => $all_users
            ]);
        }
    }

    public function followed(Follower $follower)
    {
        $user = auth()->user();
        $user_ids_arr = [];
        // followersテーブルからフォローされているユーザーIDデータを取得する
        $ids = $follower->followedIds($user->id);
        foreach ($ids as $id) {
            // 取得したユーザーIDデータからIDのみ抜き出し、配列に格納する
            array_push($user_ids_arr, $id->following_id);
        }
        //$user_ids_arrが空欄かどうか判定する
        if (empty($user_ids_arr)) {
            echo '<center>フォローされているアカウントはいません</center>';
        } else {
            // フォロワーーユーザーid配列を引数に、ユーザー情報を取得する
            $all_users = $user->getUsers($user_ids_arr);
            //users/index.bladeで表示処理
            return view('users.index', [
                'all_users'  => $all_users
            ]);
        }
    }
}
