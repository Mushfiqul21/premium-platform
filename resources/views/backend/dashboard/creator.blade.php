@extends('layouts.app')

@section('title', 'Creator Dashboard')

@section('content')

<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500">My Posts</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">0</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500">Premium Posts</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">0</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500">Total Earnings</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">$0</p>
    </div>

</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-base font-semibold text-gray-700 mb-4">My Recent Posts</h2>
    <p class="text-sm text-gray-400">No posts yet. <a href="#" class="text-indigo-600 hover:underline">Create your first post →</a></p>
</div>

@endsection
