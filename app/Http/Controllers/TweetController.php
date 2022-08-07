<?php

namespace App\Http\Controllers;

use App\models\Tweet;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TweetController extends Controller
{
    public function index()
    {
        $tweets = Tweet::with('user')->get();

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
    }
}
