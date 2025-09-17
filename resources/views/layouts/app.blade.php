<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BID Dashboard')</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="ml-64 p-6">
        @yield('content')
    </div>
@stack('scripts')

</body>
</html>