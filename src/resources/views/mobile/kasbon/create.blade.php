@extends('mobile.layout')

@section('title', 'Ajukan Kasbon')

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
    --border: #e5e7eb;
    --input-bg: #f8fafc;
    --input-border: #d8deea;
    --danger: #b91c1c;
    --danger-bg: rgba(239, 68, 68, 0.10);
    --danger-border: rgba(239, 68, 68, 0.22);
  }

  * {
    box-sizing: border-box;
  }

  .kasbon-form-mobile {
    min-height: 100vh;
    min-height: 100dvh;
    background: var(--bg);
    color: var(--text);
    font-family: Arial, Helvetica, sans-serif;
  }

  .kasbon-form-header {
    background: var(--header-bg);
    padding: max(16px, env(safe-area-inset-top)) 14px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }

  .kasbon-form-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
  }

  .kasbon-form-brand {
    font-size: 18px;
    font-weight: 500;
    color: #111;
    white-space: nowrap;
  }

  .kasbon-form-back {
    width: 34px;
    height: 34px;
    border: none;
    background: transparent;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    color: #111;
  }

  .kasbon-form-back svg,
  .kasbon-form-profile svg,
  .kasbon-form-search-icon svg {
    width: 22px;
    height: 22px;
    display: block;
  }

  .kasbon-form-header-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    justify-content: flex-end;
  }

  .kasbon-form-search {
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

  .kasbon-form-search input {
    border: none;
    outline: none;
    background: transparent;
    width: 100%;
    font-size: 14px;
    color: #111;
  }

  .kasbon-form-search input::placeholder {
    color: #6b7280;
  }

  .kasbon-form-profile {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #111;
    flex-shrink: 0;
  }

  .kasbon-form-body {
    display: grid;
    grid-template-columns: 72px 1fr;
    min-height: calc(100vh - 70px);
    min-height: calc(100dvh - 70px);
  }

  .kasbon-form-sidebar {
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

  .kasbon-form-main {
    padding: 16px 14px 22px;
  }

  .kasbon-form-panel {
    background: var(--card-bg);
    border-radius: 22px;
    padding: 18px 14px 20px;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
  }

  .kasbon-form-title {
    margin: 0 0 16px;
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    text-transform: uppercase;
  }

  .form-shell {
    display: grid;
    gap: 14px;
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

  .form-input,
  .form-textarea {
    width: 100%;
    border-radius: 15px;
    border: 1px solid var(--input-border);
    background: var(--input-bg);
    color: #111827;
    padding: 14px 14px;
    font-size: 15px;
    outline: none;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    -webkit-appearance: none;
    appearance: none;
  }

  .form-input {
    min-height: 52px;
  }

  .form-textarea {
    min-height: 120px;
    resize: vertical;
  }

  .form-input::placeholder,
  .form-textarea::placeholder {
    color: #9ca3af;
  }

  .form-input:focus,
  .form-textarea:focus {
    border-color: #90a4f7;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(75, 123, 236, 0.12);
  }

  .error-text {
    margin-top: 6px;
    font-size: 12px;
    color: var(--danger);
    line-height: 1.5;
  }

  .button-group {
    display: grid;
    gap: 10px;
    margin-top: 4px;
  }

  .submit-btn,
  .cancel-btn {
    width: 100%;
    min-height: 54px;
    border-radius: 16px;
    font-size: 15px;
    font-weight: 700;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.15s ease, background 0.2s ease, border-color 0.2s ease;
  }

  .submit-btn {
    border: none;
    background: var(--primary);
    color: #fff;
    cursor: pointer;
  }

  .submit-btn:hover {
    background: var(--primary-dark);
  }

  .cancel-btn {
    background: #f8fafc;
    border: 1px solid var(--input-border);
    color: #374151;
  }

  .cancel-btn:hover {
    background: #eef2f7;
  }

  .submit-btn:active,
  .cancel-btn:active {
    transform: scale(0.99);
  }

  @media (max-width: 390px) {
    .kasbon-form-body {
      grid-template-columns: 64px 1fr;
    }

    .kasbon-form-sidebar {
      padding: 16px 8px 18px;
      gap: 22px;
    }

    .side-icon {
      width: 40px;
      height: 40px;
    }

    .kasbon-form-main {
      padding: 14px 10px 18px;
    }

    .kasbon-form-panel {
      padding: 16px 12px 18px;
    }

    .kasbon-form-search {
      width: min(150px, 42vw);
    }
  }
</style>

<div class="kasbon-form-mobile">
  <div class="kasbon-form-header">
    <div class="kasbon-form-header-left">
      <div class="kasbon-form-brand">RKU</div>

      <button type="button" class="kasbon-form-back" aria-label="Kembali">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M15 18l-6-6 6-6"/>
        </svg>
      </button>
    </div>

    <div class="kasbon-form-header-right">
      <div class="kasbon-form-search">
        <span class="kasbon-form-search-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="7"></circle>
            <path d="M20 20l-3.5-3.5"></path>
          </svg>
        </span>
        <input type="text" placeholder="Search">
      </div>

      <div class="kasbon-form-profile" aria-label="Profile">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 5.5A1.5 1.5 0 0 1 5.5 4h13A1.5 1.5 0 0 1 20 5.5v13A1.5 1.5 0 0 1 18.5 20h-13A1.5 1.5 0 0 1 4 18.5v-13Z"/>
          <circle cx="12" cy="10" r="3"/>
          <path d="M7.5 17c1.2-2 3-3 4.5-3s3.3 1 4.5 3"/>
        </svg>
      </div>
    </div>
  </div>

  <div class="kasbon-form-body">
    <aside class="kasbon-form-sidebar">
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

      <a href="{{ route('m.kasbon.index') }}" class="side-icon active" aria-label="Kasbon">
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

    <main class="kasbon-form-main">
      <div class="kasbon-form-panel">
        <h2 class="kasbon-form-title">Ajukan Kasbon</h2>

        <form method="POST" action="{{ route('m.kasbon.store') }}" class="form-shell">
          @csrf

          <div class="form-card">
            <div class="form-group">
              <label class="form-label">Nominal</label>
              <input
                type="number"
                name="nominal"
                value="{{ old('nominal') }}"
                class="form-input"
                placeholder="contoh: 500000"
                required
              >
              @error('nominal')
                <p class="error-text">{{ $message }}</p>
              @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Tenor (X kali)</label>
              <input
                type="number"
                name="tenor"
                value="{{ old('tenor', 1) }}"
                class="form-input"
                required
                min="1"
                max="12"
              >
              @error('tenor')
                <p class="error-text">{{ $message }}</p>
              @enderror
            </div>

            <div class="form-group">
              <label class="form-label">Alasan Pengajuan</label>
              <textarea
                name="alasan_pengajuan"
                class="form-textarea"
                rows="4"
                required
              >{{ old('alasan_pengajuan') }}</textarea>
              @error('alasan_pengajuan')
                <p class="error-text">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <div class="button-group">
            <button type="submit" class="submit-btn">
              Kirim Pengajuan
            </button>

            <a href="{{ route('m.kasbon.index') }}" class="cancel-btn">
              Batal
            </a>
          </div>
        </form>
      </div>
    </main>
  </div>
</div>
@endsection