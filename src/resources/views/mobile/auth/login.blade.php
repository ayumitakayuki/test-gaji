<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Karyawan</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-slate-900 text-slate-100 flex items-center justify-center p-4">
    <div class="w-full max-w-sm rounded-2xl border border-slate-700 bg-slate-800/60 p-6 shadow">
        <h1 class="text-xl font-bold mb-1">Login Karyawan</h1>
        <p class="text-sm text-slate-400 mb-6">Masuk untuk mengajukan kasbon</p>

        @if ($errors->any())
            <div class="bg-rose-500/15 text-rose-200 border border-rose-500/30 text-sm p-3 rounded-xl mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('m.login.post') }}" class="space-y-4">
            @csrf

            <div>
                <label class="text-sm font-medium text-slate-200">Email</label>
                <input type="email" name="email" required
                    class="w-full mt-1 rounded-xl bg-slate-900/60 border border-slate-700 text-slate-100 placeholder:text-slate-500 p-3 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
            </div>

            <div>
                <label class="text-sm font-medium text-slate-200">Password</label>
                <input type="password" name="password" required
                    class="w-full mt-1 rounded-xl bg-slate-900/60 border border-slate-700 text-slate-100 placeholder:text-slate-500 p-3 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
            </div>

            <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-xl font-semibold">
                Masuk
            </button>
        </form>

        <p class="text-xs text-slate-500 mt-5">
            Tips: izin kamera & lokasi akan diminta saat masuk menu Absensi.
        </p>
    </div>
</body>
</html>