<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('author')->latest()->get();
        return view('backend.posts.index', compact('posts'));
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')
                         ->with('success', 'Post deleted successfully!');
    }
}
