<!-- resources/views/biddings.blade.php -->

@extends('layouts.app')

@section('title', 'Bidding Management')

@section('content')

<!-- Header + Add Button -->
<div class="flex justify-between items-center mb-6 bg-gradient-to-r from-bid-green to-bid-orange p-4 rounded-lg shadow-md">
    <h1 class="text-2xl font-semibold text-green">Biddings Management</h1>
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
        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bid Submission</th>
        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
    </tr>
</thead>
<tbody class="bg-white divide-y divide-gray-200">
    @forelse($biddings as $bidding)
    <tr class="hover:bg-bid-green/10 cursor-pointer" onclick="toggleDetails({{ $loop->iteration }})">
        <td class="px-4 py-3">{{ $loop->iteration }}</td>
        <td class="px-4 py-3 truncate max-w-xs" title="{{ $bidding->project_name }}">{{ $bidding->project_name }}</td>
        <td class="px-4 py-3">₱{{ number_format($bidding->abc, 2) }}</td>
        <td class="px-4 py-3">{{ $bidding->category ?? 'N/A' }}</td>
        <td class="px-4 py-3">
    {{ $bidding->bid_submission ? \Carbon\Carbon::parse($bidding->bid_submission)->format('M d, Y') : 'N/A' }}
</td>

        <td class="px-4 py-3 flex space-x-2">
            <!-- Edit -->
            <button onclick="event.stopPropagation(); openModal(
                'edit',
                {{ $bidding->id }},
                '{{ addslashes($bidding->project_name) }}',
                '{{ $bidding->abc }}',
                '{{ $bidding->pre_bid }}',
                 '{{ $bidding->prep_date }}',
                '{{ $bidding->bid_submission }}',
                '{{ $bidding->bid_opening }}',
                {{ $bidding->lgu_id }},
                '{{ addslashes($bidding->lgu->envelope_system ?? '') }}',
                '{{ addslashes($bidding->solicitation_number ?? '') }}',
                '{{ addslashes($bidding->reference_number ?? '') }}',
                '{{ addslashes($bidding->delivery_schedule ?? '') }}', 
                    '{{ addslashes($bidding->category ?? '') }}'  <!-- ✅ new -->
            )" class="px-3 py-1 bg-blue-100 text-blue-600 rounded hover:bg-blue-200">
                Edit
            </button>

            <!-- Delete -->
            <form action="{{ route('biddings.destroy', $bidding->id) }}" method="POST"
                  onsubmit="event.stopPropagation(); return confirm('Are you sure you want to delete this bidding?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200">
                    Delete
                </button>
            </form>

            <!-- Docs Dropdown -->
            <div class="relative inline-block text-left">
                <button type="button"
                    class="px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 flex items-center"
                    onclick="event.stopPropagation(); toggleDropdown('docs-{{ $bidding->id }}'); toggleDetails({{ $loop->iteration }})">
                    <i class="fas fa-file-word mr-1"></i> Docs
                </button>
                <div id="docs-{{ $bidding->id }}"
                     class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg z-50 border">
                    @forelse($documents as $doc)
                        <a href="{{ route('biddings.generate', ['bidding' => $bidding->id, 'document' => $doc->id]) }}"
                           class="block px-4 py-2 text-gray-700 hover:bg-bid-green hover:text-white">
                            <i class="fas fa-file-word mr-2 text-blue-500"></i> {{ $doc->title }}
                        </a>
                    @empty
                        <p class="px-4 py-2 text-gray-500">No templates uploaded.</p>
                    @endforelse
                </div>
            </div>
        </td>
    </tr>

    <!-- Collapsible Details -->
   
<tr id="details-{{ $loop->iteration }}" class="bg-gray-50 hidden">
 <td colspan="6" class="p-0">
    <div class="p-4">
        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600 mb-4">
            <div><strong>Solicitation #:</strong> {{ $bidding->solicitation_number ?? 'N/A' }}</div>
            <div><strong>Reference #:</strong> {{ $bidding->reference_number ?: 'N/A' }}</div>
            <div><strong>Delivery Schedule:</strong> {{ $bidding->delivery_schedule ?: 'N/A' }}</div>
            <div><strong>Pre-bid:</strong>
                {{ $bidding->pre_bid ? \Carbon\Carbon::parse($bidding->pre_bid)->format('M d, Y h:i A') : 'N/A' }}
            </div>
               <p><strong>Preparation Date:</strong> 
        {{ $bidding->prep_date ? \Carbon\Carbon::parse($bidding->prep_date)->format('F j, Y') : 'N/A' }}
    </p> <!-- 🆕 added -->
            <div><strong>Bid Opening:</strong>
                {{ $bidding->bid_opening ? \Carbon\Carbon::parse($bidding->bid_opening)->format('M d, Y h:i A') : 'N/A' }}
            </div>
            <div><strong>Procuring Entity:</strong> {{ $bidding->lgu->name ?? 'N/A' }}</div>
            <div><strong>Envelope System:</strong> {{ $bidding->lgu->envelope_system ?? 'N/A' }}</div>
       </div>


        <!-- ===== Document selection form (Step 1) ===== -->
        <form id="zipForm-{{ $bidding->id }}" action="{{ route('biddings.downloadZip', $bidding->id) }}" method="POST">
            @csrf

            <div class="mb-2 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="selectAll-{{ $bidding->id }}" class="select-all">
                    <label for="selectAll-{{ $bidding->id }}" class="text-sm text-gray-700">Select all</label>
                </div>
                <div class="text-xs text-gray-500">Choose templates to include in the ZIP for this bidding</div>
            </div>

            <div class="grid grid-cols-1 gap-2 max-h-48 overflow-y-auto border rounded p-2 bg-white">
                @foreach($documents as $doc)
                    <label class="flex items-center space-x-2 py-1 px-2 rounded hover:bg-gray-50">
                        <input type="checkbox" name="document_ids[]" value="{{ $doc->id }}" class="doc-checkbox">
                        <span class="text-sm">{{ $doc->title }} @if($doc->lgu_id) <span class="text-xs text-gray-400">({{ $doc->lgu->name ?? 'LGU' }})</span> @endif</span>
                    </label>
                @endforeach
                @if($documents->isEmpty())
                    <div class="text-sm text-gray-500 px-2">No templates available.</div>
                @endif
            </div>

            <div class="mt-3 flex items-center justify-end space-x-2">
                <button type="button" onclick="toggleDetails({{ $loop->iteration }});" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">Close</button>
                <button type="submit" id="downloadBtn-{{ $bidding->id }}" class="px-4 py-2 rounded-lg bg-bid-green text-white hover:bg-bid-dark-green download-zip-btn" disabled>
                    Download Selected as ZIP
                </button>
            </div>
        </form>
        <!-- ===== end selection form ===== -->
    </td>
    @empty
    <tr>
        <td colspan="5" class="px-4 py-3 text-center text-gray-500">
            No biddings found.
        </td>
    </tr>
@endforelse
</tbody>
</tr>


<!-- Modal -->
<div id="biddingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg w-96 p-6 relative">
        <h2 id="modalTitle" class="text-xl font-semibold mb-4 text-bid-green"></h2>
        <form id="biddingForm" method="POST">
            @csrf
            <input type="hidden" id="bidId" name="bid_id">
            <!-- _method will be appended dynamically for Edit -->
            
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
                <input type="datetime-local" name="pre_bid" id="preBid" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-bid-green outline-none" >
            </div>

            <div class="mb-4">
    <label class="block text-gray-700 mb-1">Preparation Date</label>
    <input type="date" name="prep_date" id="prepDate"
           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-bid-green outline-none"
           required>
</div>


            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Bid Submission</label>
                <input type="datetime-local" name="bid_submission" id="bidSubmission" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-bid-green outline-none" >
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Bid Opening</label>
                <input type="datetime-local" name="bid_opening" id="bidOpening" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-bid-green outline-none" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Solicitation Number</label>
                <input type="text" name="solicitation_number" id="solicitationNumber"
                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-bid-green outline-none">
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
                <label class="block text-gray-700 mb-1">Category</label>
                <input type="text" name="category" id="category"
                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-bid-green outline-none">
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
    document.addEventListener('DOMContentLoaded', function () {
    // initialize each bidding form
    document.querySelectorAll('form[id^="zipForm-"]').forEach(form => {
        const bidId = form.id.replace('zipForm-', '');
        const checkboxes = form.querySelectorAll('input.doc-checkbox');
        const selectAll = form.querySelector('.select-all');
        const downloadBtn = document.getElementById('downloadBtn-' + bidId);

        const updateButton = () => {
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
            if (downloadBtn) downloadBtn.disabled = !anyChecked;
            if (selectAll) selectAll.checked = checkboxes.length && Array.from(checkboxes).every(cb => cb.checked);
        };

        // toggle all
        if (selectAll) {
            selectAll.addEventListener('change', function () {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                updateButton();
            });
        }

        checkboxes.forEach(cb => cb.addEventListener('change', updateButton));
        updateButton();
    });
});
document.getElementById('lguId').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const envelopeSystem = selectedOption.getAttribute('data-envelope') || '';
    document.getElementById('envelopeSystem').value = envelopeSystem;
});

function openModal(action, id = '', project = '', abc = '', preBid = '', prepDate = '', bidSub = '', bidOpen = '', lguId = '', envelopeSystem = '',solicitationNumber = '', referenceNumber = '', deliverySchedule = '', category = '') {
    const modal = document.getElementById('biddingModal');
    modal.classList.remove('hidden');
    const form = document.getElementById('biddingForm');

    let methodInput = document.getElementById('formMethod');
    if(methodInput) methodInput.remove();

    if(action === 'add') {
        document.getElementById('modalTitle').innerText = 'Add Bidding Project';
        form.action = "{{ route('biddings.store') }}";
        document.getElementById('bidId').value = '';
        document.getElementById('projectName').value = '';
        document.getElementById('abc').value = '';
        document.getElementById('preBid').value = preBid ?? '';
        document.getElementById('prepDate').value = '';

        document.getElementById('bidSubmission').value = bidSub ?? '';                   
        document.getElementById('bidOpening').value = '';
        document.getElementById('lguId').value = '';
        document.getElementById('envelopeSystem').value = '';
        document.getElementById('solicitationNumber').value = solicitationNumber;
        document.getElementById('referenceNumber').value = '';
        document.getElementById('deliverySchedule').value = '';
        document.getElementById('category').value = '';

    } else {
        document.getElementById('modalTitle').innerText = 'Edit Bidding Project';
        form.action = `/biddings/${id}`;
        document.getElementById('bidId').value = id;
        document.getElementById('projectName').value = project;
        document.getElementById('abc').value = abc;
        document.getElementById('preBid').value = preBid;
        document.getElementById('prepDate').value = prepDate ?? '';
        document.getElementById('bidSubmission').value = bidSub;
        document.getElementById('bidOpening').value = bidOpen;
        document.getElementById('lguId').value = lguId;
        document.getElementById('envelopeSystem').value = envelopeSystem;
        document.getElementById('solicitationNumber').value = solicitationNumber;
        document.getElementById('referenceNumber').value = referenceNumber;
        document.getElementById('deliverySchedule').value = deliverySchedule;
        document.getElementById('category').value = category;


        // Add _method=PUT for edit
        let methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        methodInput.id = 'formMethod';
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
function toggleDropdown(id) {
    // Hide all dropdowns first
    document.querySelectorAll('[id^="docs-"]').forEach(el => el.classList.add('hidden'));
    // Toggle the selected one
    document.getElementById(id).classList.toggle('hidden');
}

// Hide dropdown if user clicks outside
window.addEventListener('click', function(e) {
    document.querySelectorAll('[id^="docs-"]').forEach(el => {
        if (!el.contains(e.target) && !e.target.closest('button')) {
            el.classList.add('hidden');
        }
    });
});
</script>
@if(isset($openId) && $openId)
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Find the bidding row by ID
        let bidding = @json($biddings->firstWhere('id', $openId));

        if (bidding) {
            openModal(
                'edit',
                bidding.id,
                bidding.project_name,
                bidding.abc,
                bidding.pre_bid,
                bidding.prep_date,
                bidding.bid_submission,
                bidding.bid_opening,
                bidding.lgu_id,
                bidding.lgu?.envelope_system ?? '',
                bidding.solicitation_number ?? '',
                bidding.reference_number ?? '',
                bidding.delivery_schedule ?? '',
                bidding.category
            );
        }
    });
</script>
@endif

@endsection