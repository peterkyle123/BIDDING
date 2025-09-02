@extends('layouts.app')

@section('title', 'Documents')

@section('content')
    <!-- Header -->
    <div class="flex justify-between items-center mb-6 bg-gradient-to-r from-bid-green to-bid-orange p-4 rounded-lg shadow-md">
        <h1 class="text-2xl font-semibold text-green">Document Management</h1>
    </div>

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
                <label class="block text-gray-700 mb-1">Upload File</label>
                <input type="file" name="file" class="w-full px-3 py-2 border border-gray-400 rounded-lg focus:ring-2 focus:ring-bid-green outline-none" required>
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
                           <i class="fas fa-file-download mr-1"></i> Download
                        </a>
                    </td>
                    <td class="px-6 py-4 flex items-center space-x-3" onclick="event.stopPropagation()">
                        <!-- Preview button -->
                        <button type="button"
                            class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200 text-gray-800 flex items-center space-x-2"
                            onclick="openPreviewModal('{{ asset('storage/' . $doc->file_path) }}', '{{ $doc->file_name }}')">
                            <i class="fas fa-eye"></i>
                            <span>Preview</span>
                        </button>

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

    <!-- Preview Modal -->
    <div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
       <div class="bg-white rounded-lg w-[60%] h-[90%] relative p-4 shadow-lg">

            <!-- Close button -->
            <button onclick="closePreviewModal()" 
                    class="absolute top-3 right-3 text-gray-600 hover:text-black">
                <i class="fas fa-times text-xl"></i>
            </button>

            <!-- File title -->
            <h2 id="previewTitle" class="text-lg font-semibold mb-4"></h2>

            <!-- Preview area -->
            <div id="previewContent" class="w-full h-[90%] overflow-auto flex justify-center items-center border rounded">
                <!-- Dynamic content goes here -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function openPreviewModal(fileUrl, fileName) {
    const previewContent = document.getElementById('previewContent');
    const previewTitle = document.getElementById('previewTitle');

    previewTitle.innerText = fileName;
    const extension = fileUrl.split('.').pop().toLowerCase();

    if (extension === 'pdf') {
        previewContent.innerHTML = `<canvas id="pdfCanvas" class="border shadow"></canvas>`;
        renderPDF(fileUrl);
    } else if (['jpg','jpeg','png','gif'].includes(extension)) {
        previewContent.innerHTML = `<img src="${fileUrl}" class="max-w-full max-h-full border shadow object-contain" />`;
    } else {
        previewContent.innerHTML = `
            <iframe src="https://docs.google.com/viewer?url=${fileUrl}&embedded=true" 
                class="w-[794px] h-[1123px] border shadow"
                style="max-width:100%; max-height:100%;"></iframe>`;
    }

    document.getElementById('previewModal').classList.remove('hidden');
}

function closePreviewModal() {
    document.getElementById('previewModal').classList.add('hidden');
    document.getElementById('previewContent').innerHTML = "";
}

function renderPDF(url) {
    const canvas = document.getElementById('pdfCanvas');
    const ctx = canvas.getContext('2d');

    pdfjsLib.getDocument(url).promise.then(pdfDoc => {
        pdfDoc.getPage(1).then(page => {
            const viewport = page.getViewport({ scale: 1.3 });
            canvas.height = viewport.height;
            canvas.width = viewport.width;
            page.render({ canvasContext: ctx, viewport: viewport });
        });
    });
}
</script>
@endpush
