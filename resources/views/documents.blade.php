@extends('layouts.app')

@section('title', 'Documents')

@section('content')
    <!-- Header -->
    <div class="flex justify-between items-center mb-6 bg-gradient-to-r from-bid-green to-bid-orange p-4 rounded-lg shadow-md">
        <h1 class="text-2xl font-semibold text-green">Documents Management</h1>
    </div>
    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
        {{ session('success') }}
    </div>
@endif


    <!-- Upload Form -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-700 mb-1">Title</label>
                <input type="text" name="title" class="w-full px-3 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-bid-green outline-none" required>
            </div>
            <div>
                <label class="block text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-bid-green outline-none"></textarea>
            </div>
            <div>
                <label class="block text-gray-700 mb-1">Upload Word Template (.docx only)</label>
                <input type="file" name="file" accept=".docx"
                    class="w-full px-3 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-bid-green outline-none" required>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-bid-green text-white px-4 py-2 rounded-lg hover:bg-bid-dark-green">
                    <i class="fas fa-upload mr-2"></i> Upload
                </button>
            </div>
        </form>
    </div>

    <!-- Documents Table -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">File</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($documents as $doc)
                <tr class="hover:bg-bid-green/10">
                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4">{{ $doc->title }}</td>
                    <td class="px-6 py-4">{{ $doc->description }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ asset('storage/' . $doc->file_path) }}" 
                           download="{{ $doc->file_name }}"
                           class="text-blue-600 hover:underline">
                           <i class="fas fa-file-word mr-1"></i> Download
                        </a>
                    </td>
                    <td class="px-6 py-4 flex items-center space-x-3" onclick="event.stopPropagation()">
                        <!-- Delete button -->
                        <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" class="inline"
                              onsubmit="event.stopPropagation(); return confirm('Delete this document?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-50 rounded hover:bg-red-100 text-red-600 flex items-center space-x-2">
                                <i class="fas fa-trash"></i>
                                <span>Delete</span>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No documents uploaded yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
