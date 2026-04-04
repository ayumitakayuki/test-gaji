@extends('mobile.layout')

@section('title', 'Detail Penilaian Kinerja')

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

  .pk-detail-mobile {
    min-height: 100vh;
    min-height: 100dvh;
    background: var(--bg);
    color: var(--text);
    font-family: Arial, Helvetica, sans-serif;
  }

  .pk-detail-header {
    background: var(--header-bg);
    padding: max(16px, env(safe-area-inset-top)) 14px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }

  .pk-detail-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
  }

  .pk-detail-brand {
    font-size: 18px;
    font-weight: 500;
    color: #111;
    white-space: nowrap;
  }

  .pk-detail-back {
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

  .pk-detail-back svg,
  .pk-detail-profile svg,
  .pk-detail-search-icon svg {
    width: 22px;
    height: 22px;
    display: block;
  }

  .pk-detail-header-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    justify-content: flex-end;
  }

  .pk-detail-search {
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

  .pk-detail-search input {
    border: none;
    outline: none;
    background: transparent;
    width: 100%;
    font-size: 14px;
    color: #111;
  }

  .pk-detail-search input::placeholder {
    color: #6b7280;
  }

  .pk-detail-profile {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #111;
    flex-shrink: 0;
  }

  .pk-detail-body {
    display: grid;
    grid-template-columns: 72px 1fr;
    min-height: calc(100vh - 70px);
    min-height: calc(100dvh - 70px);
  }

  .pk-detail-sidebar {
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

  .pk-detail-main {
    padding: 16px 14px 22px;
  }

  .pk-detail-panel {
    background: var(--card-bg);
    border-radius: 22px;
    padding: 18px 14px 20px;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
  }

  .pk-detail-title {
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
    background: rgba(59,130,246,.14);
    color: #1d4ed8;
    border: 1px solid rgba(59,130,246,.28);
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
    .pk-detail-body {
      grid-template-columns: 64px 1fr;
    }

    .pk-detail-sidebar {
      padding: 16px 8px 18px;
      gap: 22px;
    }

    .side-icon {
      width: 40px;
      height: 40px;
    }

    .pk-detail-main {
      padding: 14px 10px 18px;
    }

    .pk-detail-panel {
      padding: 16px 12px 18px;
    }

    .pk-detail-search {
      width: min(150px, 42vw);
    }

    .detail-top {
      flex-direction: column;
      align-items: flex-start;
    }
  }
</style>

<div class="pk-detail-mobile">
  <div class="pk-detail-header">
    <div class="pk-detail-header-left">
      <div class="pk-detail-brand">RKU</div>

      <a href="{{ route('m.penilaian-kinerja.index') }}" class="pk-detail-back" aria-label="Kembali">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M15 18l-6-6 6-6"/>
        </svg>
      </a>
    </div>

    <div class="pk-detail-header-right">
      <div class="pk-detail-search">
        <span class="pk-detail-search-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="7"></circle>
            <path d="M20 20l-3.5-3.5"></path>
          </svg>
        </span>
        <input type="text" placeholder="Search">
      </div>

      <div class="pk-detail-profile" aria-label="Profile">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 5.5A1.5 1.5 0 0 1 5.5 4h13A1.5 1.5 0 0 1 20 5.5v13A1.5 1.5 0 0 1 18.5 20h-13A1.5 1.5 0 0 1 4 18.5v-13Z"/>
          <circle cx="12" cy="10" r="3"/>
          <path d="M7.5 17c1.2-2 3-3 4.5-3s3.3 1 4.5 3"/>
        </svg>
      </div>
    </div>
  </div>

  <div class="pk-detail-body">
    <aside class="pk-detail-sidebar">
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

    <main class="pk-detail-main">
      <div class="pk-detail-panel">
        <h2 class="pk-detail-title">Detail Penilaian Kinerja</h2>

        <div class="detail-card">
          <div class="detail-top">
            <div>
              <div class="detail-label">Periode Kenaikan Gaji</div>
              <div class="detail-id">{{ $penilaian->periode_kenaikan_gaji }}</div>
            </div>

            <span class="detail-badge">
              {{ $penilaian->predikat ?: '-' }}
            </span>
          </div>

          <div class="detail-section">
            <div class="detail-grid">
              <div class="detail-row"><b>Tanggal Penilaian:</b> {{ optional($penilaian->tanggal_penilaian)->format('d M Y') }}</div>
              <div class="detail-row"><b>Nilai Akhir:</b> {{ $penilaian->nilai_akhir }}</div>
              <div class="detail-row"><b>Nominal Kenaikan Gaji:</b> Rp {{ number_format($penilaian->nominal_kenaikan_gaji, 0, ',', '.') }}</div>
            </div>
          </div>

          <div class="detail-section">
            <div class="detail-row"><b>Catatan:</b></div>
            <div class="detail-text">{{ $penilaian->catatan ?: '-' }}</div>
          </div>

          <div class="detail-section">
            <div class="detail-grid">
              <div class="detail-row"><b>Disiplin:</b> {{ $penilaian->disiplin ?: '-' }}</div>
              <div class="detail-row"><b>Tanggung Jawab:</b> {{ $penilaian->tanggung_jawab ?: '-' }}</div>
              <div class="detail-row"><b>Kualitas Kerja:</b> {{ $penilaian->kualitas_kerja ?: '-' }}</div>
              <div class="detail-row"><b>Produktivitas:</b> {{ $penilaian->produktivitas ?: '-' }}</div>
              <div class="detail-row"><b>Kerja Sama:</b> {{ $penilaian->kerja_sama ?: '-' }}</div>
              <div class="detail-row"><b>Inisiatif:</b> {{ $penilaian->inisiatif ?: '-' }}</div>
            </div>
          </div>
        </div>

        <a href="{{ route('m.penilaian-kinerja.index') }}" class="back-btn">
          Kembali
        </a>
      </div>
    </main>
  </div>
</div>
@endsection