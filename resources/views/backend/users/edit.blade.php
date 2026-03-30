@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

<div class="max-w-lg mx-auto">

    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}"
           class="text-sm text-gray-500 hover:text-indigo-600">← Back to Users</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

        <h2 class="text-base font-semibold text-gray-700 mb-6">Edit User — {{ $user->name }}</h2>

        @if($errors->any())
            <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Name (readonly) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" value="{{ $user->name }}" disabled
                       class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm bg-gray-50 text-gray-400">
            </div>

            {{-- Email (readonly) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="text" value="{{ $user->email }}" disabled
                       class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm bg-gray-50 text-gray-400">
            </div>

            {{-- Role --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}"
                            {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="is_active"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="1" {{ $user->is_active ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="bg-indigo-600 text-white text-sm px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                    Update User
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="text-sm text-gray-500 hover:underline">Cancel</a>
            </div>

        </form>
    </div>
</div>

@endsection
