@extends('layouts.app')

@section('title', 'View LGU')

@section('content')

<!-- Header -->
<div class="flex justify-between items-center mb-6 bg-gradient-to-r from-bid-green to-bid-orange p-4 rounded-lg shadow-md">
    <h1 class="text-2xl font-semibold text-white">View LGU</h1>
    <a href="{{ route('lgus.index') }}" 
       class="bg-white text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-100 flex items-center space-x-2">
        <i class="fas fa-arrow-left"></i>
        <span>Back</span>
    </a>
</div>

<!-- LGU Details -->
<div class="bg-white shadow-sm rounded-lg p-6 mb-8">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ $lgu->name }}</h2>
    <div class="space-y-2 text-gray-700">
        <p><span class="font-semibold">Name:</span> {{ $lgu->name }}</p>
        <p><span class="font-semibold">Location:</span> {{ $lgu->location }}</p>
        <p><span class="font-semibold">Envelope System:</span> {{ $lgu->envelope_system }}</p>
        <p><span class="font-semibold">BAC Chairman:</span> {{ $lgu->bac_chairman }}</p>
    </div>
</div>

<!-- Bidding History -->
<div class="bg-white shadow-sm rounded-lg p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Bidding History</h2>

    @if($lgu->biddings && $lgu->biddings->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg">
                <thead class="bg-gray-100 text-gray-800">
                    <tr>
                        <th class="px-4 py-2 border-b">Project Name</th>
                        <th class="px-4 py-2 border-b">ABC</th>
                        <th class="px-4 py-2 border-b">Pre-Bid</th>
                        <th class="px-4 py-2 border-b">Bid Submission</th>
                        <th class="px-4 py-2 border-b">Bid Opening</th>
                        <th class="px-4 py-2 border-b">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lgu->biddings as $bidding)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border-b">{{ $bidding->project_name }}</td>
                            <td class="px-4 py-2 border-b">{{ number_format($bidding->abc, 2) }}</td>
                            <td class="px-4 py-2 border-b">{{ $bidding->pre_bid ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border-b">{{ $bidding->bid_submission ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border-b">{{ $bidding->bid_opening ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border-b">
                                <span class="px-2 py-1 text-xs rounded 
                                    {{ $bidding->status === 'Awarded' ? 'bg-green-100 text-green-700' : 
                                       ($bidding->status === 'Pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                                    {{ $bidding->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-600">No bidding history available for this LGU.</p>
    @endif
</div>

@endsection
