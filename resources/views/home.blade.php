@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
  <!-- Header -->
  <header class="bg-white shadow-sm border-b border-gray-200">
    <div class="px-6 py-4 flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-semibold text-gray-900">Dashboard Overview</h1>
        <p class="text-gray-600 text-sm mt-1">Welcome back!</p>
      </div>
      <div class="flex items-center space-x-4">
        <div class="relative">
          <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
          <input type="text" placeholder="Search..." 
            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bid-green outline-none">
        </div>
        <button class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
          <i class="fas fa-bell text-xl"></i>
          <span class="absolute -top-1 -right-1 w-5 h-5 bg-bid-orange text-white text-xs rounded-full flex items-center justify-center">3</span>
        </button>
      </div>
    </div>
  </header>

  <!-- Dashboard Content -->
  <main class="p-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <!-- Entities Card -->
      <a href="{{ route('lgus.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow flex justify-between items-center">
        <div>
            <p class="text-sm font-medium text-gray-600">Entities</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\LGU::count() }}</p>
        </div>
        <div class="w-12 h-12 bg-bid-green bg-opacity-10 rounded-lg flex items-center justify-center">
            <i class="fas fa-city text-bid-green text-xl"></i>
        </div>
      </a>

      <!-- Biddings Card -->
      <a href="{{ route('biddings.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow flex justify-between items-center">
        <div>
            <p class="text-sm font-medium text-gray-600">Biddings</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Bidding::count() }}</p>
            <span class="text-bid-green text-sm font-medium flex items-center mt-1">
                <i class="fas fa-arrow-up mr-1"></i>8% vs last month
            </span>
        </div>
        <div class="w-12 h-12 bg-bid-green bg-opacity-10 rounded-lg flex items-center justify-center">
            <i class="fas fa-handshake text-bid-green text-xl"></i>
        </div>
      </a>

      <!-- Files Card -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow flex justify-between items-center">
        <div>
          <p class="text-sm font-medium text-gray-600">Files</p>
          <p class="text-3xl font-bold text-gray-900 mt-2">57</p>
        </div>
        <div class="w-12 h-12 bg-bid-orange bg-opacity-10 rounded-lg flex items-center justify-center">
          <i class="fas fa-file-alt text-bid-orange text-xl"></i>
        </div>
      </div>
    </div>

    <!-- Bottom Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Recent Activity -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activities</h3>
          <ul id="recent-activity" class="space-y-4"></ul>
      </div>

      <!-- System Status -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Bid Status</h3>
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
              <span class="w-3 h-3 bg-bid-green rounded-full"></span>
              <span class="text-sm text-gray-900">Server Status</span>
            </div>
            <span class="text-sm text-bid-green font-medium">Online</span>
          </div>
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
              <span class="w-3 h-3 bg-bid-green rounded-full"></span>
              <span class="text-sm text-gray-900">Database</span>
            </div>
            <span class="text-sm text-bid-green font-medium">Active</span>
          </div>
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
              <span class="w-3 h-3 bg-bid-orange rounded-full"></span>
              <span class="text-sm text-gray-900">API Status</span>
            </div>
            <span class="text-sm text-bid-orange font-medium">Warning</span>
          </div>
          <div class="mt-6">
            <div class="flex justify-between text-sm text-gray-600 mb-2">
              <span>Server Load</span>
              <span>68%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div class="bg-bid-green h-2 rounded-full" style="width: 68%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection


@push('scripts')
<script>
async function fetchRecentBiddings() {
    try {
        let response = await fetch('/recent-biddings');
        if (!response.ok) throw new Error('Failed to fetch recent biddings');

        let result = await response.json();
        let biddings = result.data || [];
        let container = document.getElementById('recent-activity');
        container.innerHTML = '';

        if (!biddings.length) {
            container.innerHTML = '<li class="text-sm text-gray-500">No biddings available.</li>';
            return;
        }

        biddings.forEach(bidding => {
            // safely escape quotes
            let name = bidding.project_name ? bidding.project_name.replace(/"/g, '&quot;') : '';
            let created = bidding.created_human || '';

            container.innerHTML += `
                <li class="flex items-start space-x-3">
                    <span class="w-2 h-2 bg-bid-orange rounded-full mt-2"></span>
                    <div>
                        <a href="/biddings?open=${bidding.id}" class="text-sm text-gray-900 hover:underline">
                            "${name}" bidding was added
                        </a>
                        <p class="text-xs text-gray-500">${created}</p>
                    </div>
                </li>
            `;
        });

    } catch (error) {
        console.error("❌ Error fetching biddings:", error);
    }
}

// run on page load
fetchRecentBiddings();
// refresh every 60 seconds
setInterval(fetchRecentBiddings, 60000);
</script>
@endpush



