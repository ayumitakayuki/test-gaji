@extends('mobile.layout')

@section('title', 'SLIP GAJI')

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
    --soft-box: #eef2ff;
    --border: #e5e7eb;
  }

  * { box-sizing: border-box; }

  .pk-mobile {
    min-height: 100vh;
    min-height: 100dvh;
    background: var(--bg);
    color: var(--text);
    font-family: Arial, Helvetica, sans-serif;
  }

  .pk-header {
    background: var(--header-bg);
    padding: max(16px, env(safe-area-inset-top)) 14px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }

  .pk-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
  }

  .pk-brand {
    font-size: 18px;
    font-weight: 500;
    color: #111;
    white-space: nowrap;
  }

  .pk-back {
    width: 34px;
    height: 34px;
    border: none;
    background: transparent;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    color: #111;
    text-decoration: none;
  }

  .pk-back svg,
  .pk-profile svg,
  .pk-search-icon svg {
    width: 22px;
    height: 22px;
    display: block;
  }

  .pk-header-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    justify-content: flex-end;
  }

  .pk-search {
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

  .pk-search input {
    border: none;
    outline: none;
    background: transparent;
    width: 100%;
    font-size: 14px;
    color: #111;
  }

  .pk-search input::placeholder {
    color: #6b7280;
  }

  .pk-profile {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #111;
    flex-shrink: 0;
  }

  .pk-body {
    display: grid;
    grid-template-columns: 72px 1fr;
    min-height: calc(100vh - 70px);
    min-height: calc(100dvh - 70px);
  }

  .pk-sidebar {
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

  .pk-main {
    padding: 16px 14px 22px;
  }

  .pk-panel {
    background: var(--card-bg);
    border-radius: 22px;
    padding: 18px 14px 20px;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
  }

  .pk-title {
    margin: 0 0 16px;
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    text-transform: uppercase;
  }

  .pk-list {
    display: grid;
    gap: 12px;
  }

  .pk-item {
    display: block;
    text-decoration: none;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 15px 14px;
    transition: transform 0.15s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    color: inherit;
  }

  .pk-item:hover {
    transform: translateY(-1px);
    border-color: #cfd8f6;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
  }

  .pk-item-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px;
  }

  .pk-period {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    line-height: 1.3;
  }

  .pk-date {
    margin-top: 4px;
    font-size: 12px;
    color: var(--muted);
  }

  .pk-badge {
    padding: 7px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
    background: rgba(59,130,246,.14);
    color: #1d4ed8;
    border: 1px solid rgba(59,130,246,.28);
  }

  .pk-grid {
    margin-top: 12px;
    display: grid;
    gap: 8px;
  }

  .pk-row {
    font-size: 14px;
    color: #374151;
    line-height: 1.5;
  }

  .pk-row b {
    color: #111827;
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
    .pk-body { grid-template-columns: 64px 1fr; }
    .pk-sidebar { padding: 16px 8px 18px; gap: 22px; }
    .side-icon { width: 40px; height: 40px; }
    .pk-main { padding: 14px 10px 18px; }
    .pk-panel { padding: 16px 12px 18px; }
    .pk-search { width: min(150px, 42vw); }
  }
</style>

<div class="pk-mobile">
  <div class="pk-header">
    <div class="pk-header-left">
      <div class="pk-brand">RKU</div>

      <a href="{{ route('m.dashboard') }}" class="pk-back" aria-label="Kembali">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M15 18l-6-6 6-6"/>
        </svg>
      </a>
    </div>

    <div class="pk-header-right">
      <div class="pk-search">
        <span class="pk-search-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="7"></circle>
            <path d="M20 20l-3.5-3.5"></path>
          </svg>
        </span>
        <input type="text" placeholder="Search">
      </div>

      <div class="pk-profile" aria-label="Profile">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 5.5A1.5 1.5 0 0 1 5.5 4h13A1.5 1.5 0 0 1 20 5.5v13A1.5 1.5 0 0 1 18.5 20h-13A1.5 1.5 0 0 1 4 18.5v-13Z"/>
          <circle cx="12" cy="10" r="3"/>
          <path d="M7.5 17c1.2-2 3-3 4.5-3s3.3 1 4.5 3"/>
        </svg>
      </div>
    </div>
  </div>

  <div class="pk-body">
    <aside class="pk-sidebar">
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
      <a href="{{ route('m.penilaian-kinerja.index') }}"
        class="side-icon {{ request()->routeIs('m.penilaian-kinerja.*') ? 'active' : '' }}"
        aria-label="Penilaian Kinerja">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 11l3 3 6-6"></path>
          <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9"></path>
        </svg>
      </a>
      <a href="{{ route('m.slip.index') }}"
        class="side-icon {{ request()->routeIs('m.slip.*') ? 'active' : '' }}"
        aria-label="Slip Gaji">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
          <rect x="4" y="3" width="16" height="18" rx="2"></rect>
          <path d="M8 7h8"></path>
          <path d="M8 11h8"></path>
          <path d="M8 15h5"></path>
        </svg>
      </a>
    </aside>

    <main class="pk-main">
      <div class="pk-panel">
        <h2 class="pk-title">Slip Gaji</h2>

        <div class="pk-list">
          @forelse($slips as $slip)
            <a href="{{ route('m.slip.pdf', $slip->id) }}" target="_blank" class="pk-item">
              <div class="pk-item-head">
                <div>
                  <div class="pk-period">
                    {{ \Carbon\Carbon::parse($slip->periode_awal)->format('d-m-Y') }}
                    s/d
                    {{ \Carbon\Carbon::parse($slip->periode_akhir)->format('d-m-Y') }}
                  </div>
                  <div class="pk-date">
                    Klik untuk melihat hasil slip gaji
                  </div>
                </div>

                <span class="pk-badge">PDF</span>
              </div>

              <div class="pk-grid">
                <div class="pk-row">
                  <b>Nama:</b> {{ $slip->nama }}
                </div>
                <div class="pk-row">
                  <b>ID Karyawan:</b> {{ $slip->id_karyawan }}
                </div>
                <div class="pk-row">
                  <b>Total Diterima:</b>
                  Rp {{ number_format($slip->grand_total ?? 0, 0, ',', '.') }}
                </div>
              </div>
            </a>
          @empty
            <div class="empty-state">
              Belum ada data slip gaji.
            </div>
          @endforelse
        </div>
      </div>
    </main>
  </div>
</div>
@endsection