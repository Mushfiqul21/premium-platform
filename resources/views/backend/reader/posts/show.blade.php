@extends('layouts.app')

@section('title', $post->title)

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('reader.posts.index') }}"
           class="text-sm text-gray-500 hover:text-indigo-600">← Back to Posts</a>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

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
            </div>

            {{-- Title --}}
            <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $post->title }}</h1>

            {{-- Meta --}}
            <div class="flex items-center gap-3 text-xs text-gray-400 mb-6">
                <span>By {{ $post->author->name }}</span>
                <span>•</span>
                <span>{{ $post->created_at->format('M d, Y') }}</span>
            </div>

            {{-- Content or Lock --}}
            @if($unlocked)
                <div class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">
                    {{ $post->body }}
                </div>
            @else
                {{-- Blurred Preview --}}
                <div class="relative">
                    <div class="text-gray-400 text-sm leading-relaxed line-clamp-3 blur-sm select-none">
                        {{ $post->body }}
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-white"></div>
                </div>

                {{-- Lock Box --}}
                <div class="mt-6 border border-indigo-100 bg-indigo-50 rounded-xl p-6 text-center">
                    <div class="text-3xl mb-2">🔒</div>
                    <h3 class="text-base font-semibold text-gray-800 mb-1">Premium Content</h3>
                    <p class="text-sm text-gray-500 mb-4">Unlock this post for <strong>${{ number_format($post->price, 2) }}</strong></p>
                    <a href="{{ route('reader.payment.checkout', $post) }}"
                       class="inline-block bg-indigo-600 text-white text-sm px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                        Unlock with Stripe 💳
                    </a>
                </div>
            @endif

        </div>
    </div>
</div>

@endsection
