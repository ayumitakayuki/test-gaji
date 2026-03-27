<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Login Karyawan</title>
    @vite('resources/css/app.css')

    <style>
        :root {
            --bg: #dfe3f3;
            --card: transparent;
            --text: #111827;
            --muted: #4b5563;
            --input-bg: #f8fafc;
            --input-border: #d6dbe7;
            --primary: #17298f;
            --primary-hover: #12206f;
            --danger-bg: rgba(239, 68, 68, 0.10);
            --danger-border: rgba(239, 68, 68, 0.25);
            --danger-text: #991b1b;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            min-height: 100%;
            font-family: Arial, Helvetica, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        body {
            min-height: 100vh;
            min-height: 100dvh;
        }

        .page {
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding:
                max(20px, env(safe-area-inset-top))
                18px
                max(20px, env(safe-area-inset-bottom))
                18px;
            background: var(--bg);
        }

        .login-shell {
            width: 100%;
            max-width: 430px;
        }

        .login-card {
            width: 100%;
            background: var(--card);
            border-radius: 28px;
            padding: 20px 22px 24px;
        }

        .brand-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 28px;
        }

        .logo-box {
            width: clamp(150px, 42vw, 210px);
            height: clamp(150px, 42vw, 210px);
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
        }

        .logo-box img {
            width: 82%;
            height: 82%;
            object-fit: contain;
            display: block;
        }

        .title {
            margin: 0;
            font-size: clamp(32px, 6vw, 42px);
            line-height: 1.1;
            font-weight: 500;
            color: #000;
        }

        .subtitle {
            margin: 10px 0 0;
            font-size: 14px;
            color: var(--muted);
        }

        .alert {
            margin-bottom: 18px;
            padding: 12px 14px;
            border-radius: 14px;
            background: var(--danger-bg);
            border: 1px solid var(--danger-border);
            color: var(--danger-text);
            font-size: 14px;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-label {
            display: block;
            margin-bottom: 10px;
            font-size: clamp(15px, 3.8vw, 18px);
            font-weight: 500;
            color: #111827;
        }

        .form-input {
            width: 100%;
            height: 58px;
            border: 1px solid var(--input-border);
            border-radius: 16px;
            background: var(--input-bg);
            padding: 0 16px;
            font-size: 16px;
            color: #111827;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            -webkit-appearance: none;
            appearance: none;
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .form-input:focus {
            border-color: #7c8fdc;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
            background: #fff;
        }

        .submit-btn {
            width: 100%;
            height: 60px;
            border: 0;
            border-radius: 16px;
            background: var(--primary);
            color: #fff;
            font-size: clamp(18px, 4.6vw, 22px);
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.15s ease;
            margin-top: 8px;
        }

        .submit-btn:hover {
            background: var(--primary-hover);
        }

        .submit-btn:active {
            transform: scale(0.99);
        }

        .footer-note {
            margin-top: 18px;
            font-size: 13px;
            line-height: 1.6;
            color: var(--muted);
            text-align: center;
        }

        @media (max-width: 390px) {
            .login-card {
                padding: 16px 16px 22px;
            }

            .brand-wrap {
                margin-bottom: 24px;
            }

            .form-group {
                margin-bottom: 18px;
            }

            .form-input {
                height: 54px;
                border-radius: 14px;
            }

            .submit-btn {
                height: 56px;
                border-radius: 14px;
            }
        }

        @media (min-width: 768px) {
            .login-shell {
                max-width: 460px;
            }

            .login-card {
                padding: 28px 28px 30px;
            }
        }
    </style>
</head>
<body>
    <main class="page">
        <section class="login-shell">
            <div class="login-card">
                <div class="brand-wrap">
                    <div class="logo-box">
                        {{-- Ganti src sesuai file logo Anda --}}
                        <img src="{{ asset('images/logorku.jpg') }}" alt="Logo RKU">
                    </div>

                    <h1 class="title">Sign in</h1>
                    <p class="subtitle">Masuk untuk mengajukan kasbon</p>
                </div>

                @if ($errors->any())
                    <div class="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('m.login.post') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="form-input"
                            placeholder="Masukkan email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                        >
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="form-input"
                            placeholder="Masukkan password"
                            required
                            autocomplete="current-password"
                        >
                    </div>

                    <button type="submit" class="submit-btn">
                        Sign in
                    </button>
                </form>

                <p class="footer-note">
                    Tips: izin kamera & lokasi akan diminta saat masuk menu Absensi.
                </p>
            </div>
        </section>
    </main>
</body>
</html>