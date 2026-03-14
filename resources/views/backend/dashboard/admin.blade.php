@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500">Total Users</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">0</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500">Total Posts</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">0</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500">Total Revenue</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">$0</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500">Active Creators</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">0</p>
    </div>

</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-base font-semibold text-gray-700 mb-4">Recent Activity</h2>
    <p class="text-sm text-gray-400">No activity yet.</p>
</div>

@endsection
