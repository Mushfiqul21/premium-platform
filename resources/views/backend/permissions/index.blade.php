@extends('layouts.app')

@section('title', 'Role & Permission Management')

@section('content')

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

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- Create Role --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-base font-semibold text-gray-700 mb-4">Create New Role</h2>
        <form method="POST" action="{{ route('admin.roles.store') }}" class="flex gap-3">
            @csrf
            <input type="text" name="name"
                   placeholder="e.g. moderator, editor"
                   class="flex-1 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <button type="submit"
                    class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                + Create
            </button>
        </form>

        {{-- All Roles List --}}
        <div class="mt-4 space-y-2">
            @foreach($roles as $role)
            <div class="flex items-center justify-between px-3 py-2 bg-gray-50 rounded-lg">
                <div>
                    <span class="text-sm font-medium text-gray-700 capitalize">{{ $role->name }}</span>
                    <span class="text-xs text-gray-400 ml-2">{{ $role->permissions->count() }} permissions</span>
                </div>
                @if(!in_array($role->name, ['admin', 'creator', 'reader']))
                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}"
                      onsubmit="return confirm('Delete this role?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-400 hover:text-red-600 text-xs">Delete</button>
                </form>
                @else
                    <span class="text-xs text-gray-300">Default</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- Create Permission --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-base font-semibold text-gray-700 mb-4">Create New Permission</h2>
        <form method="POST" action="{{ route('admin.permissions.store') }}" class="flex gap-3">
            @csrf
            <input type="text" name="name"
                   placeholder="e.g. create-posts, manage-users"
                   class="flex-1 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <button type="submit"
                    class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                + Create
            </button>
        </form>
        @error('name') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror

        {{-- All Permissions List --}}
        @if($permissions->count() > 0)
        <div class="mt-4 flex flex-wrap gap-2">
            @foreach($permissions as $permission)
            <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 px-3 py-1 rounded-full">
                <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}"
                      onsubmit="return confirm('Delete?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-400 hover:text-red-600 text-xs">✕</button>
                </form>
            </div>
            @endforeach
        </div>
        @else
            <p class="text-sm text-gray-400 mt-4">No permissions created yet.</p>
        @endif
    </div>

</div>

{{-- Assign Permissions to Roles --}}
<div class="space-y-6">
    <h2 class="text-base font-semibold text-gray-700">Assign Permissions to Roles</h2>

    @foreach($roles as $role)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

        {{-- Role Header --}}
        <div class="flex items-center gap-3 mb-5">
            <div class="w-9 h-9 rounded-full flex items-center justify-center
                {{ $role->name === 'admin' ? 'bg-red-100' : '' }}
                {{ $role->name === 'creator' ? 'bg-indigo-100' : '' }}
                {{ $role->name === 'reader' ? 'bg-green-100' : '' }}
                {{ !in_array($role->name, ['admin','creator','reader']) ? 'bg-gray-100' : '' }}">
                @if($role->name === 'admin') 👑
                @elseif($role->name === 'creator') ✍️
                @elseif($role->name === 'reader') 📖
                @else 🔰
                @endif
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-800 capitalize">{{ $role->name }}</h3>
                <p class="text-xs text-gray-400">{{ $role->permissions->count() }} permissions assigned</p>
            </div>
        </div>

        {{-- Permissions Checkboxes --}}
        @if($permissions->count() > 0)
        <form method="POST" action="{{ route('admin.permissions.assignToRole') }}">
            @csrf
            <input type="hidden" name="role" value="{{ $role->name }}">

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 mb-4">
                @foreach($permissions as $permission)
                <label class="flex items-center gap-2 px-3 py-2 rounded-lg border cursor-pointer transition
                    {{ $role->hasPermissionTo($permission->name) ? 'border-indigo-300 bg-indigo-50' : 'border-gray-200 bg-gray-50 hover:border-indigo-200' }}">
                    <input type="checkbox"
                           name="permissions[]"
                           value="{{ $permission->name }}"
                           {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-indigo-600">
                    <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                </label>
                @endforeach
            </div>

            <button type="submit"
                    class="bg-indigo-600 text-white text-sm px-5 py-2 rounded-lg hover:bg-indigo-700 transition">
                Save {{ ucfirst($role->name) }} Permissions
            </button>
        </form>
        @else
            <p class="text-sm text-gray-400">No permissions created yet.</p>
        @endif

    </div>
    @endforeach
</div>

@endsection
