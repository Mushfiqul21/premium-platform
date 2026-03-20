@extends('layouts.app')

@section('title', 'Browse Posts')

@section('content')

<div class="mb-6">
    <h2 class="text-lg font-semibold text-gray-700">Latest Posts</h2>
    <p class="text-sm text-gray-400">Browse free and premium content</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($posts as $post)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">

        {{-- Cover Image --}}
        @if($post->cover_image)
            <img src="{{ asset('storage/' . $post->cover_image) }}"
                 class="w-full h-40 object-cover">
        @else
            <div class="w-full h-40 bg-indigo-50 flex items-center justify-center">
                <svg class="w-10 h-10 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        @endif

        <div class="p-5">

            {{-- Badges --}}
            <div class="flex items-center gap-2 mb-2">
                @if($post->isPremium())
                    <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-1 rounded-full">Premium</span>
                    <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">${{ number_format($post->price, 2) }}</span>
                @else
                    <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Free</span>
                @endif
            </div>

            {{-- Title --}}
            <h3 class="text-base font-semibold text-gray-800 mb-1">{{ $post->title }}</h3>

            {{-- Excerpt --}}
            <p class="text-sm text-gray-400 mb-4">{{ Str::limit($post->body, 80) }}</p>

            {{-- Author + Date --}}
            <div class="flex items-center justify-between text-xs text-gray-400 mb-4">
                <span>By {{ $post->author->name }}</span>
                <span>{{ $post->created_at->format('M d, Y') }}</span>
            </div>

            {{-- CTA --}}
            <a href="{{ route('reader.posts.show', $post) }}"
               class="block text-center bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                @if($post->isPremium() && !Auth::user()->hasUnlocked($post))
                    🔒 Unlock for ${{ number_format($post->price, 2) }}
                @else
                    Read Post →
                @endif
            </a>

        </div>
    </div>
    @empty
    <div class="col-span-3 text-center text-gray-400 py-12">
        No posts available yet.
    </div>
    @endforelse
</div>

@endsection
