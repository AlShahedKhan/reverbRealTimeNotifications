@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6 flex items-center">
            <i class="fa fa-list mr-2"></i> {{ __('Admin - Posts List') }}
        </h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div id="notification" class="mb-4"></div>

        @if (!auth()->user()->is_admin)
            <p class="font-semibold text-lg mb-4">Create New Post</p>
            <form method="post" action="{{ route('posts.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title:</label>
                    <input type="text" name="title" id="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700">Body:</label>
                    <textarea name="body" id="body" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    @error('body')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                        <i class="fa fa-save"></i> Submit
                    </button>
                </div>
            </form>
        @endif

        <p class="font-semibold text-lg mt-8 mb-4">Post List:</p>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 bg-white shadow-sm rounded-lg">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Body</th>
                    </tr>
                </thead>
                <tbody id="posts-list" class="bg-white divide-y divide-gray-200">
                    @foreach($posts as $post)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $post->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $post->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $post->body }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
@if(auth()->user()->is_admin)
    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Admin panel listening for posts...');

            window.Echo.channel('posts')
                .listen('.create', (data) => {
                    console.log('Broadcast received in admin panel:', data);

                    const postsList = document.getElementById('posts-list');
                    postsList.insertAdjacentHTML('afterbegin', `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">${data.post.id}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${data.post.title}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${data.post.body}</td>
                        </tr>
                    `);

                    const notification = document.getElementById('notification');
                    notification.insertAdjacentHTML('beforeend', `
                        <div class="alert alert-success alert-dismissible fade show">
                            <span><i class="fa fa-circle-check"></i> ${data.message}</span>
                        </div>
                    `);
                });
        });
    </script>
@endif
@endsection
