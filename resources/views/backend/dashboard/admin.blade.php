@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500">Total Users</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">{{ \App\Models\User::count() }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500">Total Posts</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">{{ \App\Models\Post::count() }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500">Total Revenue</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">${{ number_format(\App\Models\Payment::where('status', \App\Models\Payment::STATUS_PAID)->sum('amount'), 2) }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm text-gray-500">Active Creators</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">{{ \Spatie\Permission\Models\Role::findByName('creator')->users->count() }}</p>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Recent Users --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-base font-semibold text-gray-700 mb-4">Recent Users</h2>
        <ul class="space-y-3">
            @foreach(\App\Models\User::with('roles')->latest()->take(5)->get() as $user)
            <li class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full capitalize
                    {{ $user->hasRole('admin') ? 'bg-red-100 text-red-700' : '' }}
                    {{ $user->hasRole('creator') ? 'bg-indigo-100 text-indigo-700' : '' }}
                    {{ $user->hasRole('reader') ? 'bg-green-100 text-green-700' : '' }}
                ">
                    {{ $user->getRoleNames()->first() ?? 'No Role' }}
                </span>
            </li>
            @endforeach
        </ul>
    </div>

    {{-- Recent Payments --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-base font-semibold text-gray-700 mb-4">Recent Payments</h2>
        <ul class="space-y-3">
            @foreach(\App\Models\Payment::with(['user', 'post'])->where('status', \App\Models\Payment::STATUS_PAID)->latest()->take(5)->get() as $payment)
            <li class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $payment->user->name }}</p>
                    <p class="text-xs text-gray-400">{{ $payment->post->title }}</p>
                </div>
                <span class="text-sm font-semibold text-indigo-600">${{ number_format($payment->amount, 2) }}</span>
            </li>
            @endforeach
        </ul>
    </div>

</div>

@endsection
