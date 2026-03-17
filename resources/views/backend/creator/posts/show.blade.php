@extends('layouts.app')

@section('title', $post->title)

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('creator.posts.index') }}"
           class="text-sm text-gray-500 hover:text-indigo-600">← Back to Posts</a>
        <a href="{{ route('creator.posts.edit', $post) }}"
           class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            Edit Post
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Cover Image --}}
        @if($post->cover_image)
            <img src="{{ asset('storage/' . $post->cover_image) }}"
                 class="w-full h-56 object-cover">
        @endif

        <div class="p-6">

            {{-- Badges --}}
            <div class="flex items-center gap-2 mb-3">
                @if($post->isPremium())
                    <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-1 rounded-full">Premium</span>
                    <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">${{ number_format($post->price, 2) }}</span>
                @else
                    <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Free</span>
                @endif

                @if($post->isPublished())
                    <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Published</span>
                @else
                    <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full">Draft</span>
                @endif
            </div>

            {{-- Title --}}
            <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $post->title }}</h1>

            {{-- Meta --}}
            <p class="text-xs text-gray-400 mb-6">
                Created {{ $post->created_at->format('M d, Y') }}
            </p>

            {{-- Body --}}
            <div class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">
                {{ $post->body }}
            </div>

        </div>
    </div>
</div>

@endsection
