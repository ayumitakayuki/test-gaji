@extends('mobile.layout')

@section('title', 'Riwayat Perizinan')

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
    --soft-box: #f8fafc;
    --success-bg: rgba(16, 185, 129, 0.12);
    --success-text: #065f46;
    --success-border: rgba(16, 185, 129, 0.26);
    --warning-bg: rgba(245, 158, 11, 0.12);
    --warning-text: #92400e;
    --warning-border: rgba(245, 158, 11, 0.26);
  }

  * {
    box-sizing: border-box;
  }

  .izin-list-mobile {
    min-height: 100vh;
    min-height: 100dvh;
    background: var(--bg);
    color: var(--text);
    font-family: Arial, Helvetica, sans-serif;
  }

  .izin-list-header {
    background: var(--header-bg);
    padding: max(16px, env(safe-area-inset-top)) 14px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }

  .izin-list-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
  }

  .izin-list-brand {
    font-size: 18px;
    font-weight: 500;
    color: #111;
    white-space: nowrap;
  }

  .izin-list-back {
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

  .izin-list-back svg,
  .izin-list-profile svg,
  .izin-list-search-icon svg {
    width: 22px;
    height: 22px;
    display: block;
  }

  .izin-list-header-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    justify-content: flex-end;
  }

  .izin-list-search {
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

  .izin-list-search input {
    border: none;
    outline: none;
    background: transparent;
    width: 100%;
    font-size: 14px;
    color: #111;
  }

  .izin-list-search input::placeholder {
    color: #6b7280;
  }

  .izin-list-profile {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #111;
    flex-shrink: 0;
  }

  .izin-list-body {
    display: grid;
    grid-template-columns: 72px 1fr;
    min-height: calc(100vh - 70px);
    min-height: calc(100dvh - 70px);
  }

  .izin-list-sidebar {
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

  .izin-list-main {
    padding: 16px 14px 22px;
  }

  .izin-list-panel {
    background: var(--card-bg);
    border-radius: 22px;
    padding: 18px 14px 20px;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
  }

  .izin-list-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 16px;
    flex-wrap: wrap;
  }

  .izin-list-title {
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

  .izin-list-cards {
    display: grid;
    gap: 12px;
  }

  .izin-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 15px 14px;
  }

  .izin-card-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 12px;
  }

  .izin-jenis {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
    line-height: 1.35;
  }

  .izin-status {
    padding: 7px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
    border: 1px solid transparent;
  }

  .izin-status.approved {
    background: var(--success-bg);
    color: var(--success-text);
    border-color: var(--success-border);
  }

  .izin-status.pending {
    background: var(--warning-bg);
    color: var(--warning-text);
    border-color: var(--warning-border);
  }

  .izin-grid {
    display: grid;
    gap: 8px;
  }

  .izin-row {
    font-size: 14px;
    color: #374151;
    line-height: 1.55;
  }

  .izin-row b {
    color: #111827;
  }

  .izin-approved-meta {
    margin-top: 6px;
    font-size: 12px;
    color: var(--muted);
    line-height: 1.5;
  }

  .izin-bukti {
    margin-top: 10px;
  }

  .izin-bukti a {
    display: inline-block;
    text-decoration: none;
  }

  .izin-bukti img {
    width: 72px;
    height: 72px;
    object-fit: cover;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: #f8fafc;
  }

  .approve-wrap {
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px solid var(--border);
  }

  .approve-btn {
    border: none;
    background: var(--primary);
    color: #fff;
    padding: 10px 14px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s ease, transform 0.15s ease;
  }

  .approve-btn:hover {
    background: var(--primary-dark);
  }

  .approve-btn:active {
    transform: scale(0.99);
  }

  .approve-disabled {
    font-size: 13px;
    color: #9ca3af;
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
    .izin-list-body {
      grid-template-columns: 64px 1fr;
    }

    .izin-list-sidebar {
      padding: 16px 8px 18px;
      gap: 22px;
    }

    .side-icon {
      width: 40px;
      height: 40px;
    }

    .izin-list-main {
      padding: 14px 10px 18px;
    }

    .izin-list-panel {
      padding: 16px 12px 18px;
    }

    .izin-list-search {
      width: min(150px, 42vw);
    }

    .izin-card-head {
      flex-direction: column;
      align-items: flex-start;
    }

    .ajukan-btn {
      width: 100%;
    }
  }
</style>

<div class="izin-list-mobile">
  <div class="izin-list-header">
    <div class="izin-list-header-left">
      <div class="izin-list-brand">RKU</div>

      <button type="button" class="izin-list-back" aria-label="Kembali">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M15 18l-6-6 6-6"/>
        </svg>
      </button>
    </div>

    <div class="izin-list-header-right">
      <div class="izin-list-search">
        <span class="izin-list-search-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="7"></circle>
            <path d="M20 20l-3.5-3.5"></path>
          </svg>
        </span>
        <input type="text" placeholder="Search">
      </div>

      <div class="izin-list-profile" aria-label="Profile">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 5.5A1.5 1.5 0 0 1 5.5 4h13A1.5 1.5 0 0 1 20 5.5v13A1.5 1.5 0 0 1 18.5 20h-13A1.5 1.5 0 0 1 4 18.5v-13Z"/>
          <circle cx="12" cy="10" r="3"/>
          <path d="M7.5 17c1.2-2 3-3 4.5-3s3.3 1 4.5 3"/>
        </svg>
      </div>
    </div>
  </div>

  <div class="izin-list-body">
    <aside class="izin-list-sidebar">
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

      <a href="{{ route('m.perizinan.index') }}" class="side-icon active" aria-label="Perizinan">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
          <rect x="5" y="4" width="14" height="16" rx="2"></rect>
          <path d="M9 8h6"></path>
          <path d="M9 12h6"></path>
          <path d="M9 16h4"></path>
        </svg>
      </a>
    </aside>

    <main class="izin-list-main">
      <div class="izin-list-panel">
        <div class="izin-list-topbar">
          <h2 class="izin-list-title">Riwayat Perizinan</h2>

          <a href="{{ route('m.perizinan.create') }}" class="ajukan-btn">
            Ajukan Perizinan
          </a>
        </div>

        <div class="izin-list-cards">
          @forelse ($perizinan as $izin)
            <div class="izin-card">
              <div class="izin-card-head">
                <div class="izin-jenis">
                  {{ ucwords(str_replace('_', ' ', $izin->jenis)) }}
                </div>

                @if($izin->is_approved)
                  <span class="izin-status approved">Approved</span>
                @else
                  <span class="izin-status pending">Pending</span>
                @endif
              </div>

              <div class="izin-grid">
                <div class="izin-row">
                  <b>Keterangan:</b> {{ $izin->keterangan ?? '-' }}
                </div>

                <div class="izin-row">
                  <b>Mulai:</b> {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d-m-Y') }}
                </div>

                <div class="izin-row">
                  <b>Selesai:</b> {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d-m-Y') }}
                </div>

                <div class="izin-row">
                  <b>Bukti:</b>
                  @if($izin->bukti_path)
                    <div class="izin-bukti">
                      <a href="{{ Storage::url($izin->bukti_path) }}" target="_blank">
                        <img src="{{ Storage::url($izin->bukti_path) }}" alt="Bukti">
                      </a>
                    </div>
                  @else
                    -
                  @endif
                </div>

                <div class="izin-row">
                  <b>Status:</b>
                  @if($izin->is_approved)
                    Approved
                    <div class="izin-approved-meta">
                      oleh {{ optional($izin->approvedBy)->name ?? '-' }}<br>
                      {{ \Carbon\Carbon::parse($izin->approved_at)->format('d-m-Y H:i') }}
                    </div>
                  @else
                    Pending
                  @endif
                </div>
              </div>

              @can('admin')
                <div class="approve-wrap">
                  @if(! $izin->is_approved)
                    <form action="{{ route('perizinan.approve', $izin->id) }}"
                          method="POST"
                          onsubmit="return confirm('Setujui perizinan ini?');">
                      @csrf
                      <button type="submit" class="approve-btn">
                        Approve
                      </button>
                    </form>
                  @else
                    <span class="approve-disabled">-</span>
                  @endif
                </div>
              @endcan
            </div>
          @empty
            <div class="empty-state">
              Belum ada data.
            </div>
          @endforelse
        </div>
      </div>
    </main>
  </div>
</div>
@endsection