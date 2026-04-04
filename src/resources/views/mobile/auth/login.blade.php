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

        .password-field {
            position: relative;
        }

        .password-input {
            padding-right: 52px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            width: 28px;
            height: 28px;
            border: 0;
            background: transparent;
            color: #6b7280;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password:hover {
            color: var(--primary);
        }

        .toggle-password:focus {
            outline: none;
        }

        .icon-eye {
            width: 22px;
            height: 22px;
            display: block;
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
                    <p class="subtitle">Absensi dan Slip Gaji Karyawan</p>
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

                        <div class="password-field">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-input password-input"
                                placeholder="Masukkan password"
                                required
                                autocomplete="current-password"
                            >

                            <button
                                type="button"
                                class="toggle-password"
                                aria-label="Tampilkan password"
                                aria-pressed="false"
                            >
                                <svg class="icon-eye eye-open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>

                                <svg class="icon-eye eye-closed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.477 10.48A3 3 0 0012 15a3 3 0 002.12-.879" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.88 5.09A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.132 5.411" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.228 6.228A9.965 9.965 0 002.458 12c1.274 4.057 5.064 7 9.542 7a9.95 9.95 0 005.772-1.772" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">
                        Sign in
                    </button>
                </form>

                <p class="footer-note">
                    Note: izin kamera & lokasi akan diminta saat masuk menu Absensi.
                </p>
            </div>
        </section>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.querySelector('.toggle-password');
            const eyeOpen = document.querySelector('.eye-open');
            const eyeClosed = document.querySelector('.eye-closed');

            if (passwordInput && toggleButton && eyeOpen && eyeClosed) {
                toggleButton.addEventListener('click', function () {
                    const isHidden = passwordInput.type === 'password';

                    passwordInput.type = isHidden ? 'text' : 'password';
                    eyeOpen.style.display = isHidden ? 'none' : 'block';
                    eyeClosed.style.display = isHidden ? 'block' : 'none';
                    toggleButton.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
                    toggleButton.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
                });
            }
        });
    </script>
</body>
</html>