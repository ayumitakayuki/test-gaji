@extends('mobile.layout')

@section('title', 'Detail Kasbon')

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
    --border: #e5e7eb;
    --soft-box: #f8fafc;
  }

  * {
    box-sizing: border-box;
  }

  .kasbon-detail-mobile {
    min-height: 100vh;
    min-height: 100dvh;
    background: var(--bg);
    color: var(--text);
    font-family: Arial, Helvetica, sans-serif;
  }

  .kasbon-detail-header {
    background: var(--header-bg);
    padding: max(16px, env(safe-area-inset-top)) 14px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }

  .kasbon-detail-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
  }

  .kasbon-detail-brand {
    font-size: 18px;
    font-weight: 500;
    color: #111;
    white-space: nowrap;
  }

  .kasbon-detail-back {
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

  .kasbon-detail-back svg,
  .kasbon-detail-profile svg,
  .kasbon-detail-search-icon svg {
    width: 22px;
    height: 22px;
    display: block;
  }

  .kasbon-detail-header-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    justify-content: flex-end;
  }

  .kasbon-detail-search {
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

  .kasbon-detail-search input {
    border: none;
    outline: none;
    background: transparent;
    width: 100%;
    font-size: 14px;
    color: #111;
  }

  .kasbon-detail-search input::placeholder {
    color: #6b7280;
  }

  .kasbon-detail-profile {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #111;
    flex-shrink: 0;
  }

  .kasbon-detail-body {
    display: grid;
    grid-template-columns: 72px 1fr;
    min-height: calc(100vh - 70px);
    min-height: calc(100dvh - 70px);
  }

  .kasbon-detail-sidebar {
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

  .kasbon-detail-main {
    padding: 16px 14px 22px;
  }

  .kasbon-detail-panel {
    background: var(--card-bg);
    border-radius: 22px;
    padding: 18px 14px 20px;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
  }

  .kasbon-detail-title {
    margin: 0 0 16px;
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    text-transform: uppercase;
  }

  .detail-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 16px 14px;
  }

  .detail-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 14px;
  }

  .detail-label {
    font-size: 12px;
    color: var(--muted);
    margin-bottom: 4px;
  }

  .detail-id {
    font-size: 22px;
    font-weight: 700;
    color: #111827;
    line-height: 1.2;
  }

  .detail-badge {
    padding: 7px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
  }

  .detail-section {
    border-top: 1px solid var(--border);
    padding-top: 14px;
    margin-top: 14px;
  }

  .detail-grid {
    display: grid;
    gap: 10px;
  }

  .detail-row {
    font-size: 14px;
    line-height: 1.6;
    color: #374151;
  }

  .detail-row b {
    color: #111827;
  }

  .detail-text {
    margin-top: 8px;
    font-size: 14px;
    line-height: 1.7;
    color: #6b7280;
    white-space: pre-line;
  }

  .back-btn {
    display: flex;
    width: 100%;
    min-height: 54px;
    align-items: center;
    justify-content: center;
    text-align: center;
    text-decoration: none;
    border-radius: 16px;
    background: #f8fafc;
    border: 1px solid #d8deea;
    color: #374151;
    font-size: 15px;
    font-weight: 700;
    margin-top: 14px;
    transition: transform 0.15s ease, background 0.2s ease;
  }

  .back-btn:hover {
    background: #eef2f7;
  }

  .back-btn:active {
    transform: scale(0.99);
  }

  @media (max-width: 390px) {
    .kasbon-detail-body {
      grid-template-columns: 64px 1fr;
    }

    .kasbon-detail-sidebar {
      padding: 16px 8px 18px;
      gap: 22px;
    }

    .side-icon {
      width: 40px;
      height: 40px;
    }

    .kasbon-detail-main {
      padding: 14px 10px 18px;
    }

    .kasbon-detail-panel {
      padding: 16px 12px 18px;
    }

    .kasbon-detail-search {
      width: min(150px, 42vw);
    }

    .detail-top {
      flex-direction: column;
      align-items: flex-start;
    }
  }
</style>

<div class="kasbon-detail-mobile">
  <div class="kasbon-detail-header">
    <div class="kasbon-detail-header-left">
      <div class="kasbon-detail-brand">RKU</div>

      <button type="button" class="kasbon-detail-back" aria-label="Kembali">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M15 18l-6-6 6-6"/>
        </svg>
      </button>
    </div>

    <div class="kasbon-detail-header-right">
      <div class="kasbon-detail-search">
        <span class="kasbon-detail-search-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="7"></circle>
            <path d="M20 20l-3.5-3.5"></path>
          </svg>
        </span>
        <input type="text" placeholder="Search">
      </div>

      <div class="kasbon-detail-profile" aria-label="Profile">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 5.5A1.5 1.5 0 0 1 5.5 4h13A1.5 1.5 0 0 1 20 5.5v13A1.5 1.5 0 0 1 18.5 20h-13A1.5 1.5 0 0 1 4 18.5v-13Z"/>
          <circle cx="12" cy="10" r="3"/>
          <path d="M7.5 17c1.2-2 3-3 4.5-3s3.3 1 4.5 3"/>
        </svg>
      </div>
    </div>
  </div>

  <div class="kasbon-detail-body">
    <aside class="kasbon-detail-sidebar">
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

    <main class="kasbon-detail-main">
      <div class="kasbon-detail-panel">
        <h2 class="kasbon-detail-title">Detail Kasbon</h2>

        <div class="detail-card">
          <div class="detail-top">
            <div>
              <div class="detail-label">ID Pengajuan</div>
              <div class="detail-id">#{{ $kasbon->id }}</div>
            </div>

            @php
                $status = strtolower($kasbon->status_awal);
                $badge = 'background:#374151;color:#f9fafb;border:1px solid #4b5563;';
                if (str_contains($status, 'waiting')) $badge = 'background:rgba(245,158,11,.14);color:#92400e;border:1px solid rgba(245,158,11,.28);';
                if (str_contains($status, 'approved')) $badge = 'background:rgba(16,185,129,.14);color:#065f46;border:1px solid rgba(16,185,129,.28);';
                if (str_contains($status, 'rejected')) $badge = 'background:rgba(239,68,68,.12);color:#991b1b;border:1px solid rgba(239,68,68,.25);';
            @endphp

            <span class="detail-badge" style="{{ $badge }}">
              {{ $kasbon->status_awal }}
            </span>
          </div>

          <div class="detail-section">
            <div class="detail-grid">
              <div class="detail-row"><b>Tanggal:</b> {{ $kasbon->tanggal_pengajuan->format('d M Y') }}</div>
              <div class="detail-row"><b>Nominal:</b> Rp {{ number_format($kasbon->nominal,0,',','.') }}</div>
              <div class="detail-row"><b>Tenor:</b> {{ $kasbon->tenor }}x</div>
              <div class="detail-row"><b>Cicilan:</b> Rp {{ number_format($kasbon->cicilan,0,',','.') }}</div>
            </div>
          </div>

          <div class="detail-section">
            <div class="detail-row"><b>Alasan:</b></div>
            <div class="detail-text">{{ $kasbon->alasan_pengajuan }}</div>
          </div>

          <div class="detail-section">
            <div class="detail-grid">
              <div class="detail-row"><b>Prioritas:</b> {{ $kasbon->prioritas ?? '-' }}</div>
              <div class="detail-row"><b>Catatan Staff:</b> <span style="color:#6b7280;">{{ $kasbon->catatan_staff ?? '-' }}</span></div>
            </div>
          </div>
        </div>

        <a href="{{ route('m.kasbon.index') }}" class="back-btn">
          Kembali
        </a>
      </div>
    </main>
  </div>
</div>
@endsection