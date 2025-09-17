import './bootstrap';
window.openModal = function(action, id = '', project = '', abc = '', preBid = '', prepDate = '', bidSub = '', bidOpen = '', lguId = '', envelopeSystem = '', solicitationNumber = '', referenceNumber = '', deliverySchedule = '' , category = '') {
    const modal = document.getElementById('biddingModal');
    if (!modal) return; // modal only exists in biddings page
    modal.classList.remove('hidden');
    const form = document.getElementById('biddingForm');

    let methodInput = document.getElementById('formMethod');
    if(methodInput) methodInput.remove();

    if(action === 'add') {
        document.getElementById('modalTitle').innerText = 'Add Bidding Project';
        form.action = "/biddings";
        document.getElementById('bidId').value = '';
        document.getElementById('projectName').value = '';
        document.getElementById('abc').value = '';
        document.getElementById('prepDate').value = '';
        document.getElementById('preBid').value = '';
        document.getElementById('bidSubmission').value = '';
        document.getElementById('bidOpening').value = '';
        document.getElementById('lguId').value = '';
        document.getElementById('envelopeSystem').value = '';
        document.getElementById('solicitationNumber').value = '';
        document.getElementById('referenceNumber').value = '';
        document.getElementById('deliverySchedule').value = '';
    } else {
        document.getElementById('modalTitle').innerText = 'Edit Bidding Project';
        form.action = `/biddings/${id}`;
        document.getElementById('bidId').value = id;
        document.getElementById('projectName').value = project;
        document.getElementById('abc').value = abc;
        document.getElementById('prepDate').value = prepDate;
        document.getElementById('preBid').value = preBid;

        document.getElementById('bidSubmission').value = bidSub;
        document.getElementById('bidOpening').value = bidOpen;
        document.getElementById('lguId').value = lguId;
        document.getElementById('envelopeSystem').value = envelopeSystem;
        document.getElementById('solicitationNumber').value = solicitationNumber;
        document.getElementById('referenceNumber').value = referenceNumber;
        document.getElementById('deliverySchedule').value = deliverySchedule;
        document.getElementById('category').value = category;
        // Add _method=PUT
        let methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        methodInput.id = 'formMethod';
        form.appendChild(methodInput);
    }
};

window.closeModal = function() {
    const modal = document.getElementById('biddingModal');
    if (!modal) return;
    modal.classList.add('hidden');
};
function changeStatus(biddingId, newStatus) {
    fetch(`/biddings/${biddingId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            alert('Status updated to ' + data.status);
            location.reload();
        } else {
            alert(data.message);
        }
    });
}
