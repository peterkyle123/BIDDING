Sidebar
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
    <!-- Dashboard -->
    <a href="{{ route('home') }}" 
       class="flex items-center px-4 py-3 rounded-lg group
       {{ request()->routeIs('home') ? 'bg-bid-green text-white' : 'text-gray-300 hover:bg-bid-green hover:text-white' }}">
        <i class="fas fa-home mr-3
           {{ request()->routeIs('home') ? 'text-bid-orange' : 'text-gray-400 group-hover:text-white' }}"></i>
        Dashboard
    </a>

    <!-- Entities -->
    <a href="{{ route('lgus.index') }}" 
       class="flex items-center px-4 py-3 rounded-lg group
       {{ request()->routeIs('lgus.*') ? 'bg-bid-green text-white' : 'text-gray-300 hover:bg-bid-green hover:text-white' }}">
        <i class="fas fa-users mr-3
           {{ request()->routeIs('lgus.*') ? 'text-bid-orange' : 'text-gray-400 group-hover:text-white' }}"></i>
        Entity
    </a>

 <!-- Documents -->
    <a href="{{ route('documents.index') }}" 
       class="flex items-center px-4 py-3 rounded-lg group
       {{ request()->routeIs('documents.*') ? 'bg-bid-green text-white' : 'text-gray-300 hover:bg-bid-green hover:text-white' }}">
        <i class="fas fa-chart-bar mr-3
           {{ request()->routeIs('documents.*') ? 'text-bid-orange' : 'text-gray-400 group-hover:text-white' }}"></i>
        Documents
    </a>

    <!-- Biddings -->
    <a href="{{ route('biddings.index') }}" 
       class="flex items-center px-4 py-3 rounded-lg group
       {{ request()->routeIs('biddings.*') ? 'bg-bid-green text-white' : 'text-gray-300 hover:bg-bid-green hover:text-white' }}">
        <i class="fas fa-box mr-3
           {{ request()->routeIs('biddings.*') ? 'text-bid-orange' : 'text-gray-400 group-hover:text-white' }}"></i>
        Biddings
    </a>
    <!-- Settings -->
    <a href="#"
       class="flex items-center px-4 py-3 rounded-lg group
       {{ request()->routeIs('settings.*') ? 'bg-bid-green text-white' : 'text-gray-300 hover:bg-bid-green hover:text-white' }}">
        <i class="fas fa-cog mr-3
           {{ request()->routeIs('settings.*') ? 'text-bid-orange' : 'text-gray-400 group-hover:text-white' }}"></i>
        Settings
    </a>
  </nav>

  <!-- User Info -->
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
