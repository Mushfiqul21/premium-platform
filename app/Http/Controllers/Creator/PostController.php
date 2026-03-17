<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::where('user_id', Auth::id())->latest()->get();
        return view('backend.creator.posts.index', ["posts" => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $post = new Post();
        return view('backend.creator.posts.create', ["post" => $post]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'body'        => 'required|string',
            'type'        => 'required|in:1,2',
            'price'       => 'nullable|numeric|min:0',
            'cover_image' => 'nullable|image|max:2048',
            'status'      => 'required|in:0,1',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
        }

        Post::create([
            'user_id'     => Auth::id(),
            'title'       => $request->title,
            'body'        => $request->body,
            'type'        => $request->type,
            'price'       => $request->type == Post::TYPE_PREMIUM ? $request->price : 0.00,
            'cover_image' => $coverPath,
            'status'      => $request->status,
        ]);

        return redirect()->route('creator.posts.index')->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
       public function show(Post $post)
    {
        abort_if($post->user_id !== Auth::id(), 403);
        return view('backend.creator.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        abort_if($post->user_id !== Auth::id(), 403);
        return view('backend.creator.posts.create', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        abort_if($post->user_id !== Auth::id(), 403);

        $request->validate([
            'title'       => 'required|string|max:255',
            'body'        => 'required|string',
            'type'        => 'required|in:1,2',
            'price'       => 'required_if:type,2|numeric|min:0',
            'cover_image' => 'nullable|image|max:2048',
            'status'      => 'required|in:0,1',
        ]);

        $coverPath = $post->cover_image;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
        }

        $post->update([
            'title'       => $request->title,
            'body'        => $request->body,
            'type'        => $request->type,
            'price'       => $request->type == Post::TYPE_PREMIUM ? $request->price : 0.00,
            'cover_image' => $coverPath,
            'status'      => $request->status,
        ]);

        return redirect()->route('creator.posts.index')
            ->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        abort_if($post->user_id !== Auth::id(), 403);
        $post->delete();
        return redirect()->route('creator.posts.index')
            ->with('success', 'Post deleted successfully!');
    }
}
