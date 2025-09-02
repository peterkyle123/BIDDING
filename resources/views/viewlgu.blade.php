<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BID Dashboard - View LGU</title>

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
    <!-- Header -->
    <div class="flex justify-between items-center mb-6 bg-gradient-to-r from-bid-green to-bid-orange p-4 rounded-lg shadow-md">
        <h1 class="text-2xl font-semibold text-white">View LGU</h1>
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

</body>
</html>
