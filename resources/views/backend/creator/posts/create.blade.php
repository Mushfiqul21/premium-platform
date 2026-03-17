@extends('layouts.app')

@section('title', $post->exists ? 'Edit Post' : 'New Post')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-700">
            {{ $post->exists ? 'Edit Post' : 'Create New Post' }}
        </h2>
    </div>

    <form method="POST"
          action="{{ $post->exists ? route('creator.posts.update', $post) : route('creator.posts.store') }}"
          enctype="multipart/form-data"
          class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
        @csrf
        @if($post->exists) @method('PUT') @endif

        {{-- Title --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input type="text" name="title" value="{{ old('title', $post->title) }}"
                   class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                   placeholder="Post title...">
            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Body --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
            <textarea name="body" rows="8"
                      class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                      placeholder="Write your post content...">{{ old('body', $post->body) }}</textarea>
            @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Cover Image --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Cover Image</label>
            @if($post->exists && $post->cover_image)
                <img src="{{ asset('storage/' . $post->cover_image) }}"
                     class="w-32 h-20 object-cover rounded-lg mb-2">
            @endif
            <input type="file" name="cover_image" accept="image/*"
                   class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm">
            @error('cover_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Type --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Post Type</label>
            <select name="type" id="postType"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                    onchange="togglePrice()">
                <option value="1" {{ old('type', $post->type) == 1 ? 'selected' : '' }}>Free</option>
                <option value="2" {{ old('type', $post->type) == 2 ? 'selected' : '' }}>Premium</option>
            </select>
            @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Price (shown only for premium) --}}
        <div id="priceField" class="{{ old('type', $post->type) == 2 ? '' : 'hidden' }}">
            <label class="block text-sm font-medium text-gray-700 mb-1">Price ($)</label>
            <input type="number" name="price" value="{{ old('price', $post->price ?? 0) }}"
                   step="0.01" min="0"
                   class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                   placeholder="e.g. 5.00">
            @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Status --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <option value="0" {{ old('status', $post->status) == 0 ? 'selected' : '' }}>Draft</option>
                <option value="1" {{ old('status', $post->status) == 1 ? 'selected' : '' }}>Published</option>
            </select>
            @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                    class="bg-indigo-600 text-white text-sm px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                {{ $post->exists ? 'Update Post' : 'Create Post' }}
            </button>
            <a href="{{ route('creator.posts.index') }}"
               class="text-sm text-gray-500 hover:underline">Cancel</a>
        </div>

        {{-- Validation Errors --}}
@if($errors->any())
    <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    </form>
</div>


<script>
    function togglePrice() {
        const type = document.getElementById('postType').value;
        const priceField = document.getElementById('priceField');
        priceField.classList.toggle('hidden', type !== '2');
    }
</script>

@endsection
