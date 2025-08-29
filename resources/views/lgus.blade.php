<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BID Dashboard - LGU Management</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <!-- Tailwind + FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bid-green': '#22c55e',
                        'bid-dark-green': '#15803d',
                        'bid-orange': '#f97316',
                        'bid-dark-orange': '#ea580c',
                        'bid-light': '#fefce8'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-bid-light min-h-screen">

<!-- Sidebar -->
<div class="fixed inset-y-0 left-0 w-64 bg-bid-dark-green shadow-xl z-50">
    <div class="flex items-center justify-center h-16 bg-bid-green">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                <i class="fas fa-cube text-bid-green text-lg"></i>
            </div>
            <span class="text-white text-xl font-bold">BID</span>
        </div>
    </div>
   <nav class="mt-8 px-4 space-y-2">
    <a href="{{ url('/home') }}" 
       class="flex items-center px-4 py-3 rounded-lg group
       text-gray-300 hover:bg-bid-green hover:text-white">
        <i class="fas fa-home mr-3 
           {{ request()->is('home') ? 'text-bid-orange' : 'text-gray-400 group-hover:text-white' }}">
        </i>
        Dashboard
    </a>

    <a href="{{ route('lgus.index') }}" 
       class="flex items-center px-4 py-3 rounded-lg group
       text-gray-300 hover:bg-bid-green hover:text-white">
        <i class="fas fa-users mr-3 
           {{ request()->is('lgus*') ? 'text-bid-orange' : 'text-gray-400 group-hover:text-white' }}">
        </i>
        Entity
    </a>

    <a href="#" class="flex items-center px-4 py-3 rounded-lg group
       text-gray-300 hover:bg-bid-green hover:text-white">
        <i class="fas fa-chart-bar mr-3 text-gray-400 group-hover:text-white"></i>
        Documents
    </a>

    <a href="{{ route('biddings.index') }}" class="flex items-center px-4 py-3 rounded-lg group
       text-gray-300 hover:bg-bid-green hover:text-white">
        <i class="fas fa-box mr-3 text-gray-400 group-hover:text-white"></i>
        Bidding
    </a>

    <a href="#" class="flex items-center px-4 py-3 rounded-lg group
       text-gray-300 hover:bg-bid-green hover:text-white">
        <i class="fas fa-cog mr-3 text-gray-400 group-hover:text-white"></i>
        Settings
    </a>
</nav>
    <div class="absolute bottom-4 left-4 right-4">
        <div class="bg-bid-dark-green rounded-lg p-4 flex items-center space-x-3">
            <img src="https://cdn-icons-png.flaticon.com/512/17003/17003310.png" alt="Admin" class="w-10 h-10 rounded-full">
            <div>
                <p class="text-white text-sm font-medium">Admin</p>
                <p class="text-gray-300 text-xs">Administrator</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="ml-64 p-6">

    <!-- Header + Add Button -->
    <div class="flex justify-between items-center mb-6 bg-gradient-to-r from-bid-green to-bid-orange p-4 rounded-lg shadow-md">
        <h1 class="text-2xl font-semibold text-green">Procuring Entity</h1>
        <button onclick="openModal('add')" class="bg-white text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-100 flex items-center space-x-2">
            <i class="fas fa-plus"></i><span>Add Entity</span>
        </button>
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

              <td class="px-6 py-4 space-x-2">
    <!-- View Button -->
    <a href="{{ route('lgus.show', $lgu->id) }}" 
       class="text-green-600 hover:text-green-800 font-semibold">
        View
    </a>

    <!-- Edit Button -->
    <button onclick="openModal('edit', {{ $lgu->id }}, '{{ $lgu->name }}', '{{ $lgu->location }}')" 
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
                <input type="text" name="envelope_system" id="lguES" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-bid-green outline-none">
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
function openModal(action, id = '', name = '', location = '', envelope_system = '') {
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
        document.getElementById('lguES').value = action === 'add' ? '' : envelope_system;
        document.getElementById('lguId').value = '';
    } else {
        document.getElementById('modalTitle').innerText = 'Edit LGU';
        form.action = `/lgus/${id}`;
        form.method = 'POST';
        document.getElementById('lguId').value = id;
        document.getElementById('lguName').value = name;
        document.getElementById('lguLocation').value = location;
        let methodInput = document.createElement('input');
        methodInput.type =         'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);
    }
}

function closeModal() {
    document.getElementById('lguModal').classList.add('hidden');
}
</script>

</body>
</html>

