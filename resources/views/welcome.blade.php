<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to BID</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-r from-green-600 via-green-500 to-orange-500">

    <div class="text-center bg-white bg-opacity-80 p-10 rounded-2xl shadow-lg">
        <h1 class="text-5xl font-extrabold text-green-700 mb-4">
            PRIME <span class="text-orange-600">LINK</span>
        </h1>
        <p class="text-lg text-gray-700 mb-6">
            Prepare Bid Docs<span class="text-green-600 font-semibold"> in</span> a <span class="text-orange-600 font-semibold">jiffy</span> 
        </p>
        <a href="{{ url('/home') }}"
           class="px-6 py-3 bg-orange-600 text-white rounded-xl shadow hover:bg-orange-700 transition">
           Get Started
        </a>
    </div>

</body>
</html>
