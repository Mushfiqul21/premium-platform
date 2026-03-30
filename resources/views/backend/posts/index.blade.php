@extends('layouts.app')

@section('title', 'Manage Posts')

@section('content')

@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
        {{ session('success') }}
    </div>
@endif

<div class="flex items-center justify-between mb-6">
    <h2 class="text-lg font-semibold text-gray-700">All Posts</h2>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Title</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Author</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Type</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Price</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Date</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($posts as $post)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-medium text-gray-800">{{ $post->title }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $post->author->name }}</td>
                <td class="px-6 py-4">
                    @if($post->isPremium())
                        <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-1 rounded-full">Premium</span>
                    @else
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Free</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-gray-600">
                    {{ $post->isPremium() ? '$' . number_format($post->price, 2) : '—' }}
                </td>
                <td class="px-6 py-4">
                    @if($post->isPublished())
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Published</span>
                    @else
                        <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full">Draft</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-gray-400">{{ $post->created_at->format('M d, Y') }}</td>
                <td class="px-6 py-4">
                    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}"
                          onsubmit="return confirm('Delete this post?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-600 text-xs">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-400">No posts found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
