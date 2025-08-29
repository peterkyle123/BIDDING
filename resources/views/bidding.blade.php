<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BID Dashboard - Bidding</title>

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
  <!-- Logo -->
  <div class="flex items-center justify-center h-16 bg-bid-green">
    <div class="flex items-center space-x-3">
      <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
        <i class="fas fa-cube text-bid-green text-lg"></i>
      </div>
      <span class="text-white text-xl font-bold">BID</span>
    </div>
  </div>

  <!-- Navigation -->
  <nav class="mt-8 px-4 space-y-2">
    <a href="{{ url('/home') }}" 
       class="flex items-center px-4 py-3 rounded-lg group text-gray-300 hover:bg-bid-green hover:text-white">
        <i class="fas fa-home mr-3 
           {{ request()->is('home') ? 'text-bid-orange' : 'text-gray-400 group-hover:text-white' }}">
        </i>
        Dashboard
    </a>

    <a href="{{ route('lgus.index') }}" 
       class="flex items-center px-4 py-3 rounded-lg group text-gray-300 hover:bg-bid-green hover:text-white">
        <i class="fas fa-users mr-3 
           {{ request()->is('lgus*') ? 'text-bid-orange' : 'text-gray-400 group-hover:text-white' }}">
        </i>
        Entity
    </a>

    <a href="#" 
       class="flex items-center px-4 py-3 rounded-lg group text-gray-300 hover:bg-bid-green hover:text-white">
        <i class="fas fa-chart-bar mr-3 text-gray-400 group-hover:text-white"></i>
        Documents
    </a>

    <a href="{{ route('biddings.index') }}" 
       class="flex items-center px-4 py-3 rounded-lg group text-gray-300 hover:bg-bid-green hover:text-white">
        <i class="fas fa-box mr-3 
           {{ request()->is('biddings*') ? 'text-bid-orange' : 'text-gray-400 group-hover:text-white' }}">
        </i>
        Bidding
    </a>

    <a href="#" 
       class="flex items-center px-4 py-3 rounded-lg group text-gray-300 hover:bg-bid-green hover:text-white">
        <i class="fas fa-cog mr-3 text-gray-400 group-hover:text-white"></i>
        Settings
    </a>
  </nav>

  <!-- Admin Info -->
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
        <h1 class="text-2xl font-semibold text-green">Bidding Management</h1>
        <button onclick="openModal('add')" class="bg-white text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-100 flex items-center space-x-2">
            <i class="fas fa-plus"></i><span>Add Project</span>
        </button>
    </div>

    <!-- Bidding Table -->
    <div class="bg-white shadow-sm rounded-lg overflow-x-auto">
       <table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50 sticky top-0">
        <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Project Name</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ABC</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Procuring Entity</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pre-bid</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bid Submission</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bid Opening</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse($biddings as $bidding)
        <tr class="hover:bg-bid-green/10 cursor-pointer" onclick="toggleDetails({{ $loop->iteration }})">
            <td class="px-4 py-3">{{ $loop->iteration }}</td>
            <td class="px-4 py-3 truncate max-w-xs" title="{{ $bidding->project_name }}">{{ $bidding->project_name }}</td>
            <td class="px-4 py-3">₱{{ number_format($bidding->abc, 2) }}</td>
            <td class="px-4 py-3 truncate max-w-xs" title="{{ $bidding->lgu->name ?? 'N/A' }}">{{ $bidding->lgu->name ?? 'N/A' }}</td>
            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($bidding->pre_bid)->format('M d, Y') }}</td>
            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($bidding->bid_submission)->format('M d, Y') }}</td>
            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($bidding->bid_opening)->format('M d, Y') }}</td>
            <td class="px-4 py-3 relative">
                <button onclick="event.stopPropagation(); toggleActions({{ $loop->iteration }})" class="px-2 py-1 bg-gray-100 rounded-lg hover:bg-gray-200">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <div id="actions-{{ $loop->iteration }}" class="absolute right-0 mt-2 w-28 bg-white border rounded shadow-lg hidden z-10">
<button onclick="openModal(
    'edit',
    {{ $bidding->id }},
    '{{ addslashes($bidding->project_name) }}',
    '{{ $bidding->abc }}',
    '{{ $bidding->pre_bid }}',
    '{{ $bidding->bid_submission }}',
    '{{ $bidding->bid_opening }}',
    {{ $bidding->lgu_id }},
    '{{ addslashes($bidding->lgu->envelope_system ?? '') }}',
    '{{ addslashes($bidding->reference_number ?? '') }}',
    '{{ addslashes($bidding->delivery_schedule ?? '') }}'
)" class="w-full text-left px-3 py-2 hover:bg-gray-100 text-blue-600">Edit</button>

                    <form action="{{ route('biddings.destroy', $bidding->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-left px-3 py-2 hover:bg-gray-100 text-red-600">Delete</button>
                    </form>
                </div>
            </td>
        </tr>
        <!-- Collapsible Details -->
        <tr id="details-{{ $loop->iteration }}" class="bg-gray-50 hidden">
            <td colspan="8" class="px-4 py-2">
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                   <div><strong>Reference Number:</strong> {{ $bidding->reference_number ?: 'N/A' }}</div>
                    <div><strong>Delivery Schedule:</strong> {{ $bidding->delivery_schedule ?: 'N/A' }}</div>


                    <div><strong>Envelope System:</strong> {{ $bidding->lgu->envelope_system ?? 'N/A' }}</div>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="px-4 py-3 text-center text-gray-500">No biddings found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>

<!-- Modal -->
<div id="biddingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg w-96 p-6 relative">
        <h2 id="modalTitle" class="text-xl font-semibold mb-4 text-bid-green"></h2>
        <form id="biddingForm" method="POST">
            @csrf
            <input type="hidden" id="bidId" name="bid_id">

            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Project Name</label>
                <input type="text" name="project_name" id="projectName" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-bid-green outline-none" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-1">ABC</label>
                <input type="number" step="0.01" name="abc" id="abc" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-bid-green outline-none" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Pre-bid Conference</label>
                <input type="datetime-local" name="pre_bid" id="preBid" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-bid-green outline-none" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Bid Submission</label>
                <input type="datetime-local" name="bid_submission" id="bidSubmission" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-bid-green outline-none" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Bid Opening</label>
                <input type="datetime-local" name="bid_opening" id="bidOpening" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-bid-green outline-none" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Reference Number</label>
                <input type="text" name="reference_number" id="referenceNumber" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-bid-green outline-none">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Delivery Schedule</label>
                <input type="text" name="delivery_schedule" id="deliverySchedule" placeholder="e.g. 20 days from delivery of P.O" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-bid-green outline-none">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Select LGU</label>
                <select name="lgu_id" id="lguId" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-bid-green outline-none" required>
                    <option value="">-- Choose LGU --</option>
                    @foreach($lgus as $lgu)
                        <option value="{{ $lgu->id }}" data-envelope="{{ $lgu->envelope_system }}">
                            {{ $lgu->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Envelope System</label>
                <input type="text" id="envelopeSystem" class="w-full px-3 py-2 border rounded-lg bg-gray-100" readonly>
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

<script>
document.getElementById('lguId').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const envelopeSystem = selectedOption.getAttribute('data-envelope') || '';
    document.getElementById('envelopeSystem').value = envelopeSystem;
});

function openModal(action, id = '', project = '', abc = '', preBid = '', bidSub = '', bidOpen = '', lguId = '', envelopeSystem = '', referenceNumber = '', deliverySchedule = '') {
    const modal = document.getElementById('biddingModal');
    modal.classList.remove('hidden');
    const form = document.getElementById('biddingForm');
    const existingPut = form.querySelector('input[name="_method"]');
    if(existingPut) existingPut.remove();

    if(action === 'add') {
        document.getElementById('modalTitle').innerText = 'Add Bidding Project';
        form.action = "{{ route('biddings.store') }}";
        document.getElementById('bidId').value = '';
        document.getElementById('projectName').value = '';
        document.getElementById('abc').value = '';
        document.getElementById('preBid').value = '';
        document.getElementById('bidSubmission').value = '';
        document.getElementById('bidOpening').value = '';
        document.getElementById('lguId').value = '';
        document.getElementById('envelopeSystem').value = '';
        document.getElementById('referenceNumber').value = '';
        document.getElementById('deliverySchedule').value = '';
    } else {
        document.getElementById('modalTitle').innerText = 'Edit Bidding Project';
        form.action = `/biddings/${id}`;
        document.getElementById('bidId').value = id;
        document.getElementById('projectName').value = project;
        document.getElementById('abc').value = abc;
        document.getElementById('preBid').value = preBid;
        document.getElementById('bidSubmission').value = bidSub;
        document.getElementById('bidOpening').value = bidOpen;
        document.getElementById('lguId').value = lguId;
        document.getElementById('envelopeSystem').value = envelopeSystem;
        document.getElementById('referenceNumber').value = referenceNumber;
        document.getElementById('deliverySchedule').value = deliverySchedule;

        let methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);
    }
}

function closeModal() {
    document.getElementById('biddingModal').classList.add('hidden');
}

// Collapsible row toggle
function toggleDetails(index) {
    const row = document.getElementById('details-' + index);
    row.classList.toggle('hidden');
}

// Dropdown toggle
function toggleActions(index) {
    const menu = document.getElementById('actions-' + index);
    menu.classList.toggle('hidden');
}
</script>

</body>
</html>
