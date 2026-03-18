<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', 1)->latest()->get();
        return view('backend.reader.posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        abort_if($post->status !== 1, 404);

        $unlocked = false;

        if ($post->isFree()) {
            $unlocked = true;
        } elseif (Auth::user()->hasUnlocked($post)) {
            $unlocked = true;
        }

        return view('backend.reader.posts.show', compact('post', 'unlocked'));
    }
}
