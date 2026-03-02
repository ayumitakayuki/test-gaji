<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kasbon')</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="max-w-md mx-auto min-h-screen bg-gray-100">
        {{-- header --}}
        <div class="bg-white sticky top-0 z-10 border-b px-4 py-3 flex items-center justify-between">
            <h1 class="text-lg font-bold">@yield('header', 'Kasbon')</h1>
            @yield('headerAction')
        </div>

        {{-- content --}}
        <div class="p-4">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded-xl mb-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

</body>
</html>
