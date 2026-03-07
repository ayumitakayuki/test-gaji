<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Karyawan')</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-slate-900 text-slate-100">

    <div class="max-w-md mx-auto min-h-screen bg-slate-900">

        {{-- header --}}
        <div class="sticky top-0 z-10 border-b border-slate-800 bg-slate-950/80 backdrop-blur px-4 py-3 flex items-center justify-between">
            <h1 class="text-lg font-bold text-slate-100">@yield('header', 'Menu')</h1>
            <div class="text-slate-200">
                @yield('headerAction')
            </div>
        </div>

        {{-- content --}}
        <div class="p-4 space-y-3">
            @if(session('success'))
                <div class="bg-emerald-500/15 text-emerald-200 border border-emerald-500/30 px-4 py-2 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- tempat konten tampil sebagai card biar kontras --}}
            <div class="bg-slate-800/60 border border-slate-700 rounded-2xl p-4">
                @yield('content')
            </div>
        </div>

    </div>

</body>
</html>