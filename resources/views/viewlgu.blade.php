@extends('layouts.app')

@section('title', 'Documents')

@section('content')


<!-- Main Content -->

    <!-- Header -->
    <div class="flex justify-between items-center mb-6 bg-gradient-to-r from-bid-green to-bid-orange p-4 rounded-lg shadow-md">
        <h1 class="text-2xl font-semibold text-green">View LGU</h1>
        <a href="{{ route('lgus.index') }}" class="bg-white text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-100 flex items-center space-x-2">
            <i class="fas fa-arrow-left"></i>
            <span>Back</span>
        </a>
    </div>

    <!-- LGU Details -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ $lgu->name }}</h2>
        <div class="space-y-2 text-gray-700">
            <p><span class="font-semibold">Name:</span> {{ $lgu->name }}</p>
            <p><span class="font-semibold">Location:</span> {{ $lgu->location }}</p>
            <p><span class="font-semibold">Envelope System:</span> {{ $lgu->envelope_system }}</p>
             <p><span class="font-semibold">BAC Chairman:</span> {{ $lgu->bac_chairman }}</p>
        </div>
    </div>
</div>

@endsection