@extends('mobile.layout')

@section('title', 'Ganti Password')

@section('content')
<style>
  :root {
    --bg: #dfe3f3;
    --header-bg: #d9ddef;
    --sidebar-bg: #f7f7f8;
    --card-bg: #ffffff;
    --text: #111827;
    --muted: #6b7280;
    --primary: #4b7bec;
    --primary-dark: #2446d8;
    --border: #dbe2ee;
    --input-bg: #f8fafc;
    --success-bg: rgba(16, 185, 129, 0.12);
    --success-text: #065f46;
    --success-border: rgba(16, 185, 129, 0.26);
    --error-bg: rgba(239, 68, 68, 0.12);
    --error-text: #991b1b;
    --error-border: rgba(239, 68, 68, 0.24);
  }

  * {
    box-sizing: border-box;
  }

  html, body {
    overflow-x: hidden;
  }

  .password-mobile {
    min-height: 100vh;
    min-height: 100dvh;
    background: var(--bg);
    color: var(--text);
    font-family: Arial, Helvetica, sans-serif;
    width: 100%;
    overflow-x: hidden;
  }

  .password-header {
    background: var(--header-bg);
    padding: max(16px, env(safe-area-inset-top)) 14px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }

  .password-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
  }

  .password-brand {
    font-size: 18px;
    font-weight: 500;
    color: #111;
    white-space: nowrap;
  }

  .password-back {
    width: 34px;
    height: 34px;
    border: none;
    background: transparent;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    color: #111;
    flex-shrink: 0;
  }

  .password-back svg,
  .password-profile svg,
  .password-search-icon svg {
    width: 22px;
    height: 22px;
    display: block;
  }

  .password-header-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    justify-content: flex-end;
    min-width: 0;
  }

  .password-search {
    height: 34px;
    background: #fff;
    border-radius: 999px;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0 12px;
    min-width: 0;
    width: min(200px, 48vw);
    border: 1px solid rgba(0,0,0,0.04);
  }

  .password-search input {
    border: none;
    outline: none;
    background: transparent;
    width: 100%;
    min-width: 0;
    font-size: 14px;
    color: #111;
  }

  .password-search input::placeholder {
    color: #6b7280;
  }

  .password-profile {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #111;
    flex-shrink: 0;
  }

  .password-body {
    display: grid;
    grid-template-columns: 72px minmax(0, 1fr);
    min-height: calc(100vh - 70px);
    min-height: calc(100dvh - 70px);
    overflow-x: hidden;
  }

  .password-sidebar {
    background: var(--sidebar-bg);
    padding: 18px 10px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 26px;
    border-top-right-radius: 14px;
  }

  .side-icon {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #111827;
    text-decoration: none;
    transition: transform 0.15s ease, background 0.2s ease;
  }

  .side-icon.active {
    background: #eef4ff;
    color: var(--primary);
  }

  .side-icon:hover {
    transform: translateY(-1px);
    background: #f0f4ff;
  }

  .side-icon svg {
    width: 24px;
    height: 24px;
    display: block;
  }

  .password-main {
    min-width: 0;
    padding: 16px 14px 22px;
    overflow-x: hidden;
  }

  .password-panel {
    background: var(--card-bg);
    border-radius: 22px;
    padding: 18px 14px 20px;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
  }

  .password-title {
    margin: 0 0 6px;
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    text-transform: uppercase;
  }

  .password-subtitle {
    margin: 0 0 16px;
    font-size: 14px;
    line-height: 1.6;
    color: var(--muted);
  }

  .alert {
    border-radius: 16px;
    padding: 12px 14px;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 14px;
  }

  .alert-success {
    background: var(--success-bg);
    color: var(--success-text);
    border: 1px solid var(--success-border);
  }

  .alert-error {
    background: var(--error-bg);
    color: var(--error-text);
    border: 1px solid var(--error-border);
  }

  .form-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 16px 14px;
  }

  .form-group + .form-group {
    margin-top: 16px;
  }

  .form-label {
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: 700;
    color: #1f2937;
  }

  .input-wrap {
    position: relative;
  }

  .input-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
  }

  .input-icon svg {
    width: 18px;
    height: 18px;
    display: block;
  }

  .form-input {
    width: 100%;
    min-height: 52px;
    border-radius: 15px;
    border: 1px solid var(--border);
    background: var(--input-bg);
    color: #111827;
    padding: 0 14px 0 44px;
    font-size: 15px;
    outline: none;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
  }

  .form-input::placeholder {
    color: #9ca3af;
  }

  .form-input:focus {
    border-color: #90a4f7;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(75, 123, 236, 0.12);
  }

  .submit-btn {
    width: 100%;
    min-height: 54px;
    border: none;
    border-radius: 16px;
    background: var(--primary);
    color: #fff;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    margin-top: 14px;
    transition: transform 0.15s ease, background 0.2s ease;
  }

  .submit-btn:hover {
    background: var(--primary-dark);
  }

  .submit-btn:active {
    transform: scale(0.99);
  }

  @media (max-width: 390px) {
    .password-body {
      grid-template-columns: 64px minmax(0, 1fr);
    }

    .password-sidebar {
      padding: 16px 8px 18px;
      gap: 22px;
    }

    .side-icon {
      width: 40px;
      height: 40px;
    }

    .password-main {
      padding: 14px 10px 18px;
    }

    .password-panel {
      padding: 16px 12px 18px;
    }

    .password-search {
      width: min(150px, 42vw);
    }
  }
</style>

<div class="password-mobile">
  <div class="password-header">
    <div class="password-header-left">
      <div class="password-brand">RKU</div>

      <button type="button" class="password-back" aria-label="Kembali">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M15 18l-6-6 6-6"/>
        </svg>
      </button>
    </div>

    <div class="password-header-right">
      <div class="password-search">
        <span class="password-search-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="7"></circle>
            <path d="M20 20l-3.5-3.5"></path>
          </svg>
        </span>
        <input type="text" placeholder="Search">
      </div>

      <div class="password-profile" aria-label="Profile">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 5.5A1.5 1.5 0 0 1 5.5 4h13A1.5 1.5 0 0 1 20 5.5v13A1.5 1.5 0 0 1 18.5 20h-13A1.5 1.5 0 0 1 4 18.5v-13Z"/>
          <circle cx="12" cy="10" r="3"/>
          <path d="M7.5 17c1.2-2 3-3 4.5-3s3.3 1 4.5 3"/>
        </svg>
      </div>
    </div>
  </div>

  <div class="password-body">
    <aside class="password-sidebar">
      <a href="{{ route('m.dashboard') }}" class="side-icon" aria-label="Dashboard">
        <svg viewBox="0 0 24 24" fill="currentColor">
          <path d="M12 3l8 7v10a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1V10l8-7z"/>
        </svg>
      </a>

      <a href="{{ route('m.absensi.index') }}" class="side-icon" aria-label="Absensi">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="7" r="3.2"></circle>
          <path d="M5 20c.8-3.6 3.3-5.4 7-5.4S18.2 16.4 19 20"></path>
        </svg>
      </a>

      <a href="{{ route('m.absensi.history') }}" class="side-icon" aria-label="Riwayat Absensi">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 19V5"></path>
          <path d="M10 19V10"></path>
          <path d="M16 19V7"></path>
          <path d="M22 19V12"></path>
        </svg>
      </a>

      <a href="{{ route('m.kasbon.index') }}" class="side-icon" aria-label="Kasbon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 1v22"></path>
          <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14.5a3.5 3.5 0 0 1 0 7H6"></path>
        </svg>
      </a>

      <a href="{{ route('m.perizinan.index') }}" class="side-icon" aria-label="Perizinan">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
          <rect x="5" y="4" width="14" height="16" rx="2"></rect>
          <path d="M9 8h6"></path>
          <path d="M9 12h6"></path>
          <path d="M9 16h4"></path>
        </svg>
      </a>
    </aside>

    <main class="password-main">
      <div class="password-panel">
        <h1 class="password-title">Ganti Password</h1>
        <p class="password-subtitle">Isi form berikut untuk mengganti password Anda.</p>

        @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="alert alert-error">
            {{ $errors->first() }}
          </div>
        @endif

        <form method="POST" action="{{ route('m.password.update') }}">
          @csrf

          <div class="form-card">
            <div class="form-group">
              <label class="form-label">Password Lama</label>
              <div class="input-wrap">
                <span class="input-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 12.75V6a3.75 3.75 0 00-7.5 0v6.75m0 0H5.625A1.875 1.875 0 003.75 14.625v6.75A1.875 1.875 0 005.625 23.25h12.75a1.875 1.875 0 001.875-1.875v-6.75a1.875 1.875 0 00-1.875-1.875H8.25z" />
                  </svg>
                </span>
                <input
                  type="password"
                  name="current_password"
                  required
                  class="form-input"
                  placeholder="Password lama"
                >
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">Password Baru</label>
              <div class="input-wrap">
                <span class="input-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 12.75V6a3.75 3.75 0 00-7.5 0v6.75m0 0H5.625A1.875 1.875 0 003.75 14.625v6.75A1.875 1.875 0 005.625 23.25h12.75a1.875 1.875 0 001.875-1.875v-6.75a1.875 1.875 0 00-1.875-1.875H8.25z" />
                  </svg>
                </span>
                <input
                  type="password"
                  name="password"
                  required
                  class="form-input"
                  placeholder="Password baru"
                >
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">Konfirmasi Password Baru</label>
              <div class="input-wrap">
                <span class="input-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 12.75V6a3.75 3.75 0 00-7.5 0v6.75m0 0H5.625A1.875 1.875 0 003.75 14.625v6.75A1.875 1.875 0 005.625 23.25h12.75a1.875 1.875 0 001.875-1.875v-6.75a1.875 1.875 0 00-1.875-1.875H8.25z" />
                  </svg>
                </span>
                <input
                  type="password"
                  name="password_confirmation"
                  required
                  class="form-input"
                  placeholder="Ulangi password baru"
                >
              </div>
            </div>
          </div>

          <button type="submit" class="submit-btn">
            Ubah Password
          </button>
        </form>
      </div>
    </main>
  </div>
</div>
@endsection