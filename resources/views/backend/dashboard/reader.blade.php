@extends('layouts.app')

@section('title', 'Browse Content')

@section('content')

<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500">Posts Read</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">0</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500">Unlocked Posts</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">0</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500">Total Spent</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">$0</p>
    </div>

</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-base font-semibold text-gray-700 mb-4">Latest Posts</h2>
    <p class="text-sm text-gray-400">No posts available yet.</p>
</div>

@endsection
