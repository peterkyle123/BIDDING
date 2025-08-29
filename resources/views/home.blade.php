<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BID Dashboard</title>

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
<div class="ml-64">
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
          <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bid-green outline-none">
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

      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow flex justify-between items-center">
        <div>
             <a href="{{ route('lgus.index') }}">
          <p class="text-sm font-medium text-gray-600">Entities</p>
          <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\LGU::count() }}</p>

        </div>
        <div class="w-12 h-12 bg-bid-green bg-opacity-10 rounded-lg flex items-center justify-center">
          <i class="fas fa-city text-bid-green text-xl"></i>
        </div>
           </a>
      </div>


      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow flex justify-between items-center">
        <div>
          <p class="text-sm font-medium text-gray-600">Biddings</p>
          <p class="text-3xl font-bold text-gray-900 mt-2">5</p>
          <span class="text-bid-green text-sm font-medium flex items-center mt-1">
            <i class="fas fa-arrow-up mr-1"></i>8% vs last month
          </span>
        </div>
        <div class="w-12 h-12 bg-bid-green bg-opacity-10 rounded-lg flex items-center justify-center">
          <i class="fas fa-handshake text-bid-green text-xl"></i>
        </div>
      </div>

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
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
    <ul id="recent-activity" class="space-y-4">
        <!-- This will be filled by JavaScript -->
    </ul>
</div>

<script>
    async function fetchRecentBiddings() {
        try {
            let response = await fetch('/recent-biddings');
            let biddings = await response.json();

            let container = document.getElementById('recent-activity');
            container.innerHTML = '';

            if (biddings.length === 0) {
                container.innerHTML = '<li class="text-sm text-gray-500">No new biddings in the last 5 minutes.</li>';
                return;
            }

            biddings.forEach(bidding => {
                let createdAt = new Date(bidding.created_at);
                let timeAgo = timeSince(createdAt);

                container.innerHTML += `
                    <li class="flex items-start space-x-3">
                        <span class="w-2 h-2 bg-bid-orange rounded-full mt-2"></span>
                        <div>
                            <p class="text-sm text-gray-900">"${bidding.project_name}" bidding is added</p>
                            <p class="text-xs text-gray-500">${timeAgo} ago</p>
                        </div>
                    </li>
                `;
            });
        } catch (error) {
            console.error("Error fetching biddings:", error);
        }
    }

    // Helper: Convert timestamps to "2 minutes ago"
    function timeSince(date) {
        let seconds = Math.floor((new Date() - date) / 1000);
        let interval = Math.floor(seconds / 60);
        if (interval >= 1) return interval + " minute" + (interval > 1 ? "s" : "");
        return Math.floor(seconds) + " seconds";
    }

    // Load immediately + refresh every 60 seconds
    fetchRecentBiddings();
    setInterval(fetchRecentBiddings, 60000);
</script>

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
</div>

</body>
</html>
