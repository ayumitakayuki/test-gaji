<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Karyawan</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-sm bg-white rounded-2xl shadow p-6">
        <h1 class="text-xl font-bold mb-1">Login Karyawan</h1>
        <p class="text-sm text-slate-500 mb-6">Masuk untuk mengajukan kasbon</p>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 text-sm p-3 rounded-lg mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('m.login.post') }}" class="space-y-4">
            @csrf

            <div>
                <label class="text-sm font-medium">Email</label>
                <input type="email" name="email" required
                    class="w-full mt-1 rounded-xl border border-slate-300 p-3 focus:ring focus:ring-blue-200">
            </div>

            <div>
                <label class="text-sm font-medium">Password</label>
                <input type="password" name="password" required
                    class="w-full mt-1 rounded-xl border border-slate-300 p-3 focus:ring focus:ring-blue-200">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white p-3 rounded-xl font-semibold">
                Masuk
            </button>
        </form>
    </div>

</body>
</html>
