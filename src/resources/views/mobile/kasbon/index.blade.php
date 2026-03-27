@extends('mobile.layout')

@section('title', 'PENGAJUAN KASBON')

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
    --soft-box: #eef2ff;
    --border: #e5e7eb;
  }

  * {
    box-sizing: border-box;
  }

  .kasbon-mobile {
    min-height: 100vh;
    min-height: 100dvh;
    background: var(--bg);
    color: var(--text);
    font-family: Arial, Helvetica, sans-serif;
  }

  .kasbon-header {
    background: var(--header-bg);
    padding: max(16px, env(safe-area-inset-top)) 14px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }

  .kasbon-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
  }

  .kasbon-brand {
    font-size: 18px;
    font-weight: 500;
    color: #111;
    white-space: nowrap;
  }

  .kasbon-back {
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

  .kasbon-back svg,
  .kasbon-profile svg,
  .kasbon-search-icon svg {
    width: 22px;
    height: 22px;
    display: block;
  }

  .kasbon-header-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    justify-content: flex-end;
  }

  .kasbon-search {
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

  .kasbon-search input {
    border: none;
    outline: none;
    background: transparent;
    width: 100%;
    font-size: 14px;
    color: #111;
  }

  .kasbon-search input::placeholder {
    color: #6b7280;
  }

  .kasbon-profile {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #111;
    flex-shrink: 0;
  }

  .kasbon-body {
    display: grid;
    grid-template-columns: 72px 1fr;
    min-height: calc(100vh - 70px);
    min-height: calc(100dvh - 70px);
  }

  .kasbon-sidebar {
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

  .kasbon-main {
    padding: 16px 14px 22px;
  }

  .kasbon-panel {
    background: var(--card-bg);
    border-radius: 22px;
    padding: 18px 14px 20px;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
  }

  .kasbon-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 16px;
  }

  .kasbon-title {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    text-transform: uppercase;
  }

  .ajukan-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    background: var(--primary);
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    padding: 10px 14px;
    border-radius: 12px;
    transition: background 0.2s ease, transform 0.15s ease;
    white-space: nowrap;
  }

  .ajukan-btn:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
  }

  .kasbon-list {
    display: grid;
    gap: 12px;
  }

  .kasbon-item {
    display: block;
    text-decoration: none;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 15px 14px;
    transition: transform 0.15s ease, box-shadow 0.2s ease, border-color 0.2s ease;
  }

  .kasbon-item:hover {
    transform: translateY(-1px);
    border-color: #cfd8f6;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
  }

  .kasbon-item-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px;
  }

  .kasbon-nominal {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    line-height: 1.3;
  }

  .kasbon-date {
    margin-top: 4px;
    font-size: 12px;
    color: var(--muted);
  }

  .kasbon-badge {
    padding: 7px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
  }

  .kasbon-meta {
    margin-top: 12px;
    font-size: 14px;
    color: #374151;
    line-height: 1.5;
  }

  .kasbon-reason {
    margin-top: 10px;
    font-size: 13px;
    line-height: 1.55;
    color: #6b7280;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .empty-state {
    text-align: center;
    padding: 34px 16px;
    border-radius: 18px;
    background: var(--soft-box);
    color: #6b7280;
    font-size: 14px;
    line-height: 1.6;
  }

  @media (max-width: 390px) {
    .kasbon-body {
      grid-template-columns: 64px 1fr;
    }

    .kasbon-sidebar {
      padding: 16px 8px 18px;
      gap: 22px;
    }

    .side-icon {
      width: 40px;
      height: 40px;
    }

    .kasbon-main {
      padding: 14px 10px 18px;
    }

    .kasbon-panel {
      padding: 16px 12px 18px;
    }

    .kasbon-search {
      width: min(150px, 42vw);
    }

    .kasbon-topbar {
      align-items: flex-start;
      flex-direction: column;
    }

    .ajukan-btn {
      width: 100%;
    }
  }
</style>

<div class="kasbon-mobile">
  <div class="kasbon-header">
    <div class="kasbon-header-left">
      <div class="kasbon-brand">RKU</div>

      <button type="button" class="kasbon-back" aria-label="Kembali">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M15 18l-6-6 6-6"/>
        </svg>
      </button>
    </div>

    <div class="kasbon-header-right">
      <div class="kasbon-search">
        <span class="kasbon-search-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="7"></circle>
            <path d="M20 20l-3.5-3.5"></path>
          </svg>
        </span>
        <input type="text" placeholder="Search">
      </div>

      <div class="kasbon-profile" aria-label="Profile">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 5.5A1.5 1.5 0 0 1 5.5 4h13A1.5 1.5 0 0 1 20 5.5v13A1.5 1.5 0 0 1 18.5 20h-13A1.5 1.5 0 0 1 4 18.5v-13Z"/>
          <circle cx="12" cy="10" r="3"/>
          <path d="M7.5 17c1.2-2 3-3 4.5-3s3.3 1 4.5 3"/>
        </svg>
      </div>
    </div>
  </div>

  <div class="kasbon-body">
    <aside class="kasbon-sidebar">
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

    <main class="kasbon-main">
      <div class="kasbon-panel">
        <div class="kasbon-topbar">
          <h2 class="kasbon-title">Kasbon Saya</h2>

          <a href="{{ route('m.kasbon.create') }}" class="ajukan-btn">
            Ajukan
          </a>
        </div>

        <div class="kasbon-list">
          @forelse($requests as $r)
              <a href="{{ route('m.kasbon.show', $r->id) }}" class="kasbon-item">
                  <div class="kasbon-item-head">
                      <div>
                          <div class="kasbon-nominal">
                              Rp {{ number_format($r->nominal,0,',','.') }}
                          </div>
                          <div class="kasbon-date">
                              {{ $r->tanggal_pengajuan->format('d M Y') }}
                          </div>
                      </div>

                      @php
                          $status = strtolower($r->status_awal);
                          $badge = 'background:#374151;color:#f9fafb;border:1px solid #4b5563;';
                          if (str_contains($status, 'waiting')) $badge = 'background:rgba(245,158,11,.14);color:#92400e;border:1px solid rgba(245,158,11,.28);';
                          if (str_contains($status, 'approved')) $badge = 'background:rgba(16,185,129,.14);color:#065f46;border:1px solid rgba(16,185,129,.28);';
                          if (str_contains($status, 'rejected')) $badge = 'background:rgba(239,68,68,.12);color:#991b1b;border:1px solid rgba(239,68,68,.25);';
                      @endphp

                      <span class="kasbon-badge" style="{{ $badge }}">
                          {{ $r->status_awal }}
                      </span>
                  </div>

                  <div class="kasbon-meta">
                      Tenor: <b>{{ $r->tenor }}x</b> • Cicilan:
                      <b>Rp {{ number_format($r->cicilan,0,',','.') }}</b>
                  </div>

                  <div class="kasbon-reason">
                      {{ $r->alasan_pengajuan }}
                  </div>
              </a>
          @empty
              <div class="empty-state">
                  Belum ada pengajuan kasbon.
              </div>
          @endforelse
        </div>
      </div>
    </main>
  </div>
</div>
@endsection