<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'Karyawan')</title>
    @vite('resources/css/app.css')

    <style>
        html, body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        body {
            min-height: 100vh;
            min-height: 100dvh;
            background: #dfe3f3;
            color: #111827;
            font-family: Arial, Helvetica, sans-serif;
        }

        .mobile-app {
            width: 100%;
            max-width: 430px;
            margin: 0 auto;
            min-height: 100vh;
            min-height: 100dvh;
            background: transparent;
            position: relative;
        }

        @media (max-width: 640px) {
            .mobile-app {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="mobile-app">
        @yield('content')
    </div>
</body>
</html>