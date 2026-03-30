@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')

@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
        {{ session('success') }}
    </div>
@endif

<div class="flex items-center justify-between mb-6">
    <h2 class="text-lg font-semibold text-gray-700">All Users</h2>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Name</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Email</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Role</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Joined</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-medium text-gray-800">{{ $user->name }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                <td class="px-6 py-4">
                    <span class="text-xs px-2 py-1 rounded-full capitalize
                        {{ $user->hasRole('admin') ? 'bg-red-100 text-red-700' : '' }}
                        {{ $user->hasRole('creator') ? 'bg-indigo-100 text-indigo-700' : '' }}
                        {{ $user->hasRole('reader') ? 'bg-green-100 text-green-700' : '' }}
                    ">
                        {{ $user->getRoleNames()->first() ?? 'No Role' }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    @if($user->is_active)
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Active</span>
                    @else
                        <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full">Inactive</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-gray-400">{{ $user->created_at->format('M d, Y') }}</td>
                <td class="px-6 py-4 flex items-center gap-3">
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="text-indigo-600 hover:underline text-xs">Edit</a>
                    @if($user->id !== Auth::id())
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                          onsubmit="return confirm('Delete this user?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-600 text-xs">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-gray-400">No users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
