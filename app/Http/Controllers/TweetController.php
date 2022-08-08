<?php

namespace App\Http\Controllers;

use App\models\User;
use App\models\Tweet;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class TweetController extends Controller
{
    public function index()
    {
        $tweets = Tweet::with([
            'user' => fn($query) => $query->withCount([
                'followers as is_followed' => fn($query) => $query->where('follower_id', auth()->user()->id)
            ])
            ->withCasts(['is_followed' => 'boolean'])
        ])->orderBy('created_at', 'DESC')->get();

        return Inertia::render('Tweets/index', [
            'tweets' => $tweets
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => ['required', 'max:280'],
            'user_id' => ['exists:users, id']
        ]);

        Tweet::create([
            'content' => $request->input('content'),
            'user_id' => auth()->user()->id
        ]);
        return redirect::route('tweets.index');
    }

    public function followings()
    {
        $followings = Tweet::with('user')
        ->whereIn('user_id', auth()->user()->pluck('id')->toArray())
        ->orderBy('created_at', 'DESC')
        ->get();

        return Inertia::render('Tweets/Followings', [
            'followings' => $followings
        ]);
    }

    public function follows(User $user){ 
        auth()->user()->followings()->attach($user->id);

        return Redirect::route('tweets.index');
    }

    public function unfollows(User $user){
        auth()->user()->followings()->detach($user->id);

        return Redirect()->back();
    }
}
