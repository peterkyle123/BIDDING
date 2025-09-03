<!-- resources/views/lgus/index.blade.php -->
@extends('layouts.app')

@section('title', 'LGU Management')

@section('content')

    <!-- Header + Add Button -->
    <div class="flex justify-between items-center mb-6 bg-gradient-to-r from-bid-green to-bid-orange p-4 rounded-lg shadow-md">
        <h1 class="text-2xl font-semibold text-green">Procuring Entity</h1>
        <button onclick="openModal('add')" class="bg-white text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-100 flex items-center space-x-2">
            <i class="fas fa-plus"></i><span>Add Entity</span>
        </button>
    </div>

    <!-- search field -->
    <div class="mb-4">
<input 
    type="text" 
    id="searchInput" 
    placeholder="Search LGU..." 
    class="w-full px-3 py-2 border border-transparent bg-transparent rounded-lg 
           focus:ring-2 focus:ring-bid-green focus:border-bid-green outline-none 
           text-black placeholder-black italic"
    onkeyup="filterLGUs()"
/>
    

</div>
    <!-- LGU Table -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">E.S</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">BAC Chairman</th> <!-- ✅ New -->
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($lgus as $lgu)
                <tr class="hover:bg-bid-green/10">
                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4">{{ $lgu->name }}</td>
                    <td class="px-6 py-4">{{ $lgu->location }}</td>
                    <td class="px-6 py-4">{{ $lgu->envelope_system }}</td>
                    <td class="px-6 py-4">{{ $lgu->bac_chairman }}</td> <!-- ✅ Show BAC Chairman -->

              <td class="px-6 py-4 space-x-2">
    <!-- View Button -->
    <a href="{{ route('lgus.show', $lgu->id) }}" 
       class="text-green-600 hover:text-green-800 font-semibold">
        View
    </a>

    <!-- Edit Button -->
    <button onclick="openModal('edit', {{ $lgu->id }}, '{{ $lgu->name }}', '{{ $lgu->location }}','{{ $lgu->envelope_system }}','{{ $lgu->bac_chairman }}')" 
            class="text-blue-600 hover:text-blue-800 font-semibold">
        Edit
    </button>

    <!-- Delete Button -->
    <form action="{{ route('lgus.destroy', $lgu->id) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">Delete</button>
    </form>
</td>

                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No LGUs found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div id="lguModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg w-96 p-6 relative">
            <h2 id="modalTitle" class="text-xl font-semibold mb-4 text-bid-green"></h2>
            <form id="lguForm" method="POST">
                @csrf
                <input type="hidden" id="lguId" name="lgu_id">
                <div class="mb-4">
                    <label class="block text-gray-700 mb-1">Entity</label>
                    <input type="text" name="name" id="lguName" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-bid-green outline-none" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-1">Location</label>
                    <input type="text" name="location" id="lguLocation" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-bid-green outline-none" required>
                </div>
          <div class="mb-4">
    <label class="block text-gray-700 mb-1">E.S</label>
    <input 
        type="number" 
        name="envelope_system" 
        id="lguES" 
        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-bid-green outline-none"
        min="1" 
        step="1"
        placeholder="Enter number only"
        required>
</div>


        <div class="mb-4">
            <label class="block text-gray-700 mb-1">BAC Chairman</label>
            <input type="text" name="bac_chairman" id="lguBacChairman"
                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-bid-green outline-none">
        </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-bid-green text-white hover:bg-bid-dark-green">Save</button>
                </div>
            </form>
            <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>

<script>
function openModal(action, id = '', name = '', location = '', envelope_system = '', bac_chairman = '') {
    const modal = document.getElementById('lguModal');
    modal.classList.remove('hidden');
    const form = document.getElementById('lguForm');
    const existingPut = form.querySelector('input[name="_method"]');
    if(existingPut) existingPut.remove();

    if(action === 'add') {
        document.getElementById('modalTitle').innerText = 'Add LGU';
        form.action = "{{ route('lgus.store') }}";
        form.method = 'POST';
        document.getElementById('lguName').value = '';
        document.getElementById('lguLocation').value = '';
        document.getElementById('lguES').value = '';
        document.getElementById('lguBacChairman').value = ''; // ✅ reset
        document.getElementById('lguId').value = '';
    } else {
        document.getElementById('modalTitle').innerText = 'Edit LGU';
        form.action = `/lgus/${id}`;
        form.method = 'POST';
        document.getElementById('lguId').value = id;
        document.getElementById('lguName').value = name;
        document.getElementById('lguLocation').value = location;
        document.getElementById('lguES').value = envelope_system;
        document.getElementById('lguBacChairman').value = bac_chairman; // ✅ load value
        let methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);
    }
}


function closeModal() {
    document.getElementById('lguModal').classList.add('hidden');
}
function filterLGUs() {
    const input = document.getElementById("searchInput");
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll("tbody tr");

    rows.forEach(row => {
        const entityCell = row.querySelector("td:nth-child(2)"); // Entity column
        if (entityCell) {
            const originalText = entityCell.textContent;
            let text = originalText.toLowerCase();

            // Remove "municipality of " from search basis
            let strippedText = text.replace(/^municipality of\s*/i, "");

            // Reset previous highlights
            entityCell.innerHTML = originalText;

            if (filter && strippedText.startsWith(filter)) {
                row.style.display = ""; // show row

                // Highlight only the matching part (ignoring "Municipality of")
                const regex = new RegExp(`^municipality of\\s*(${filter})`, "i");
                entityCell.innerHTML = originalText.replace(regex, "Municipality of <span class='bg-yellow-300'>$1</span>");
            } else if (filter === "") {
                row.style.display = ""; // show all if empty
            } else {
                row.style.display = "none"; // hide if no match
            }
        }
    });
}
</script>

@endsection