@extends('mobile.layout')

@section('title', 'Riwayat Absensi')

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
    --table-head: #eef2ff;
    --success-bg: rgba(16, 185, 129, 0.12);
    --success-text: #065f46;
    --warning-bg: rgba(245, 158, 11, 0.12);
    --warning-text: #92400e;
  }

  * { box-sizing: border-box; }
  html, body { overflow-x: hidden; }

  .absensi-history-mobile {
    min-height: 100vh;
    min-height: 100dvh;
    background: var(--bg);
    color: var(--text);
    font-family: Arial, Helvetica, sans-serif;
    width: 100%;
    max-width: 100%;
    overflow-x: hidden;
  }

  .absensi-history-header {
    background: var(--header-bg);
    padding: max(16px, env(safe-area-inset-top)) 14px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    width: 100%;
    max-width: 100%;
  }

  .absensi-history-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
  }

  .absensi-history-brand {
    font-size: 18px;
    font-weight: 500;
    color: #111;
    white-space: nowrap;
  }

  .absensi-history-back {
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

  .absensi-history-back svg,
  .absensi-history-profile svg,
  .absensi-history-search-icon svg {
    width: 22px;
    height: 22px;
    display: block;
  }

  .absensi-history-header-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    justify-content: flex-end;
    min-width: 0;
  }

  .absensi-history-search {
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
    flex-shrink: 1;
  }

  .absensi-history-search input {
    border: none;
    outline: none;
    background: transparent;
    width: 100%;
    min-width: 0;
    font-size: 14px;
    color: #111;
  }

  .absensi-history-search input::placeholder {
    color: #6b7280;
  }

  .absensi-history-profile {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #111;
    flex-shrink: 0;
  }

  .absensi-history-body {
    display: grid;
    grid-template-columns: 72px minmax(0, 1fr);
    min-height: calc(100vh - 70px);
    min-height: calc(100dvh - 70px);
    width: 100%;
    max-width: 100%;
    overflow-x: hidden;
  }

  .absensi-history-sidebar {
    background: var(--sidebar-bg);
    padding: 18px 10px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 26px;
    border-top-right-radius: 14px;
    min-width: 0;
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
    flex-shrink: 0;
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

  .absensi-history-main {
    padding: 16px 14px 22px;
    min-width: 0;
    width: 100%;
    max-width: 100%;
    overflow-x: hidden;
  }

  .absensi-history-panel {
    background: var(--card-bg);
    border-radius: 22px;
    padding: 18px 14px 20px;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
    width: 100%;
    max-width: 100%;
    overflow: hidden;
  }

  .absensi-history-title {
    margin: 0 0 14px;
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    text-transform: uppercase;
  }

  .table-hint {
    margin-bottom: 10px;
    font-size: 12px;
    color: var(--muted);
  }

  .table-wrap {
    width: 100%;
    max-width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
    border: 1px solid var(--border);
    border-radius: 18px;
    background: #fff;
    -webkit-overflow-scrolling: touch;
  }

  .table-wrap::-webkit-scrollbar { height: 8px; }
  .table-wrap::-webkit-scrollbar-thumb {
    background: #cfd7e6;
    border-radius: 999px;
  }

  .history-table {
    width: max-content;
    min-width: 1450px;
    border-collapse: collapse;
    font-size: 13px;
    color: #374151;
  }

  .history-table thead th {
    background: var(--table-head);
    color: #1f2937;
    font-weight: 700;
    text-align: left;
    padding: 12px 10px;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
  }

  .history-table tbody td {
    padding: 12px 10px;
    border-bottom: 1px solid var(--border);
    vertical-align: top;
    white-space: nowrap;
  }

  .history-table tbody tr:last-child td {
    border-bottom: none;
  }

  .history-table tbody tr:hover {
    background: #fafcff;
  }

  .foto-thumb {
    width: 64px;
    height: 64px;
    object-fit: cover;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: #f8fafc;
    display: block;
  }

  .status-approved {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 999px;
    background: var(--success-bg);
    color: var(--success-text);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
  }

  .status-pending {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 999px;
    background: var(--warning-bg);
    color: var(--warning-text);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
  }

  .approval-meta {
    margin-top: 6px;
    font-size: 11px;
    line-height: 1.5;
    color: #6b7280;
    white-space: normal;
    min-width: 120px;
  }

  .keterangan-cell {
    min-width: 290px;
    white-space: normal !important;
    line-height: 1.5;
  }

  .ket-line {
    margin-bottom: 4px;
  }

  .ket-line b {
    color: #111827;
  }
  .ket-summary {
    font-size: 13px;
    line-height: 1.5;
    color: #4b5563;
    margin-bottom: 8px;
    white-space: normal;
  }

  .ket-toggle-btn {
    border: none;
    background: #4b7bec;
    color: #fff;
    padding: 8px 12px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s ease;
  }

  .ket-toggle-btn:hover {
    background: #2446d8;
  }

  .ket-detail-wrap {
    margin-top: 10px;
  }

  .ket-block {
    white-space: normal;
    line-height: 1.6;
    font-size: 13px;
    color: #374151;
    padding: 10px 0;
    border-top: 1px dashed #dbe2ee;
  }

  .ket-block:first-child {
    border-top: none;
    padding-top: 0;
  }

  .ket-block a {
    color: #2446d8;
    text-decoration: none;
    font-weight: 600;
  }

  .ket-block a:hover {
    text-decoration: underline;
  }
  .map-link {
    display: inline-block;
    margin-top: 6px;
    color: var(--primary-dark);
    text-decoration: none;
    font-weight: 600;
    font-size: 12px;
  }

  .map-link:hover {
    text-decoration: underline;
  }

  .approve-btn {
    border: none;
    background: var(--primary);
    color: #fff;
    padding: 8px 12px;
    border-radius: 10px;
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
    color: #9ca3af;
    font-size: 13px;
  }

  .empty-cell {
    text-align: center;
    color: #6b7280;
    padding: 18px 12px !important;
  }

  @media (max-width: 390px) {
    .absensi-history-body {
      grid-template-columns: 64px minmax(0, 1fr);
    }

    .absensi-history-sidebar {
      padding: 16px 8px 18px;
      gap: 22px;
    }

    .side-icon {
      width: 40px;
      height: 40px;
    }

    .absensi-history-main {
      padding: 14px 10px 18px;
    }

    .absensi-history-panel {
      padding: 16px 12px 18px;
    }

    .absensi-history-search {
      width: min(150px, 42vw);
    }
  }
</style>

<div class="absensi-history-mobile">
  <div class="absensi-history-header">
    <div class="absensi-history-header-left">
      <div class="absensi-history-brand">RKU</div>

      <button type="button" class="absensi-history-back" aria-label="Kembali">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M15 18l-6-6 6-6"/>
        </svg>
      </button>
    </div>

    <div class="absensi-history-header-right">
      <div class="absensi-history-search">
        <span class="absensi-history-search-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="7"></circle>
            <path d="M20 20l-3.5-3.5"></path>
          </svg>
        </span>
        <input type="text" placeholder="Search">
      </div>

      <div class="absensi-history-profile" aria-label="Profile">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 5.5A1.5 1.5 0 0 1 5.5 4h13A1.5 1.5 0 0 1 20 5.5v13A1.5 1.5 0 0 1 18.5 20h-13A1.5 1.5 0 0 1 4 18.5v-13Z"/>
          <circle cx="12" cy="10" r="3"/>
          <path d="M7.5 17c1.2-2 3-3 4.5-3s3.3 1 4.5 3"/>
        </svg>
      </div>
    </div>
  </div>

  <div class="absensi-history-body">
    <aside class="absensi-history-sidebar">
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

      <a href="{{ route('m.absensi.history') }}" class="side-icon active" aria-label="Riwayat Absensi">
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

    <main class="absensi-history-main">
      <div class="absensi-history-panel">
        <h2 class="absensi-history-title">Riwayat Absensi</h2>
        <div class="table-hint">Geser tabel ke samping untuk melihat semua kolom.</div>

        <div class="table-wrap">
          <table class="history-table">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Masuk Pagi</th>
                <th>Keluar Siang</th>
                <th>Masuk Siang</th>
                <th>Pulang Kerja</th>
                <th>Masuk Lembur</th>
                <th>Pulang Lembur</th>
                <th>Bukti Foto</th>
                <th>Keterangan</th>
                <th>Status Approval</th>
                @can('admin')
                  <th>Aksi</th>
                @endcan
              </tr>
            </thead>
            <tbody>
              @forelse ($absensi as $row)
                <tr>
                  <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d-m-Y') }}</td>
                  <td>{{ $row->masuk_pagi ? \Carbon\Carbon::parse($row->masuk_pagi)->format('H:i') : '-' }}</td>
                  <td>{{ $row->keluar_siang ? \Carbon\Carbon::parse($row->keluar_siang)->format('H:i') : '-' }}</td>
                  <td>{{ $row->masuk_siang ? \Carbon\Carbon::parse($row->masuk_siang)->format('H:i') : '-' }}</td>
                  <td>{{ $row->pulang_kerja ? \Carbon\Carbon::parse($row->pulang_kerja)->format('H:i') : '-' }}</td>
                  <td>{{ $row->masuk_lembur ? \Carbon\Carbon::parse($row->masuk_lembur)->format('H:i') : '-' }}</td>
                  <td>{{ $row->pulang_lembur ? \Carbon\Carbon::parse($row->pulang_lembur)->format('H:i') : '-' }}</td>
                  <td>
                    @if(!empty($row->bukti_foto))
                      <a href="{{ Storage::url($row->bukti_foto) }}" target="_blank">
                        <img src="{{ Storage::url($row->bukti_foto) }}" alt="Bukti Foto" class="foto-thumb">
                      </a>
                    @else
                      -
                    @endif
                  </td>
                  <td class="keterangan-cell">
                    <div class="ket-summary">
                      <b>
                        {{
                          collect([
                            $row->masuk_pagi ? 'Masuk Pagi' : null,
                            $row->keluar_siang ? 'Keluar Siang' : null,
                            $row->masuk_siang ? 'Masuk Siang' : null,
                            $row->pulang_kerja ? 'Pulang Kerja' : null,
                            $row->masuk_lembur ? 'Masuk Lembur' : null,
                            $row->pulang_lembur ? 'Pulang Lembur' : null,
                          ])->filter()->count()
                        }}
                        waktu absensi
                      </b>
                      <br>
                      Klik untuk lihat detail lokasi & foto
                    </div>

                    <button type="button" class="ket-toggle-btn" onclick="toggleKet(this)">
                      Lihat Detail
                    </button>

                    <div class="ket-detail-wrap" style="display:none;">
                      @if($row->masuk_pagi)
                        <div class="ket-block">
                          <b>Masuk Pagi</b><br>
                          Lat: {{ $row->lat_masuk_pagi ?? '-' }}<br>
                          Lng: {{ $row->lng_masuk_pagi ?? '-' }}<br>
                          Akurasi: {{ $row->accuracy_masuk_pagi ?? '-' }}<br>
                          Alamat: {{ $row->address_masuk_pagi ?? '-' }}<br>
                          @if($row->lat_masuk_pagi && $row->lng_masuk_pagi)
                            <a href="https://www.google.com/maps?q={{ $row->lat_masuk_pagi }},{{ $row->lng_masuk_pagi }}" target="_blank">Buka Lokasi</a>
                          @endif
                          @if($row->photo_path_masuk_pagi)
                            <br>
                            <a href="{{ Storage::url($row->photo_path_masuk_pagi) }}" target="_blank">Lihat Foto</a>
                          @endif
                        </div>
                      @endif

                      @if($row->keluar_siang)
                        <div class="ket-block" style="margin-top:10px;">
                          <b>Keluar Siang</b><br>
                          Lat: {{ $row->lat_keluar_siang ?? '-' }}<br>
                          Lng: {{ $row->lng_keluar_siang ?? '-' }}<br>
                          Akurasi: {{ $row->accuracy_keluar_siang ?? '-' }}<br>
                          Alamat: {{ $row->address_keluar_siang ?? '-' }}<br>
                          @if($row->lat_keluar_siang && $row->lng_keluar_siang)
                            <a href="https://www.google.com/maps?q={{ $row->lat_keluar_siang }},{{ $row->lng_keluar_siang }}" target="_blank">Buka Lokasi</a>
                          @endif
                          @if($row->photo_path_keluar_siang)
                            <br>
                            <a href="{{ Storage::url($row->photo_path_keluar_siang) }}" target="_blank">Lihat Foto</a>
                          @endif
                        </div>
                      @endif

                      @if($row->masuk_siang)
                        <div class="ket-block" style="margin-top:10px;">
                          <b>Masuk Siang</b><br>
                          Lat: {{ $row->lat_masuk_siang ?? '-' }}<br>
                          Lng: {{ $row->lng_masuk_siang ?? '-' }}<br>
                          Akurasi: {{ $row->accuracy_masuk_siang ?? '-' }}<br>
                          Alamat: {{ $row->address_masuk_siang ?? '-' }}<br>
                          @if($row->lat_masuk_siang && $row->lng_masuk_siang)
                            <a href="https://www.google.com/maps?q={{ $row->lat_masuk_siang }},{{ $row->lng_masuk_siang }}" target="_blank">Buka Lokasi</a>
                          @endif
                          @if($row->photo_path_masuk_siang)
                            <br>
                            <a href="{{ Storage::url($row->photo_path_masuk_siang) }}" target="_blank">Lihat Foto</a>
                          @endif
                        </div>
                      @endif

                      @if($row->pulang_kerja)
                        <div class="ket-block" style="margin-top:10px;">
                          <b>Pulang Kerja</b><br>
                          Lat: {{ $row->lat_pulang_kerja ?? '-' }}<br>
                          Lng: {{ $row->lng_pulang_kerja ?? '-' }}<br>
                          Akurasi: {{ $row->accuracy_pulang_kerja ?? '-' }}<br>
                          Alamat: {{ $row->address_pulang_kerja ?? '-' }}<br>
                          @if($row->lat_pulang_kerja && $row->lng_pulang_kerja)
                            <a href="https://www.google.com/maps?q={{ $row->lat_pulang_kerja }},{{ $row->lng_pulang_kerja }}" target="_blank">Buka Lokasi</a>
                          @endif
                          @if($row->photo_path_pulang_kerja)
                            <br>
                            <a href="{{ Storage::url($row->photo_path_pulang_kerja) }}" target="_blank">Lihat Foto</a>
                          @endif
                        </div>
                      @endif

                      @if($row->masuk_lembur)
                        <div class="ket-block" style="margin-top:10px;">
                          <b>Masuk Lembur</b><br>
                          Lat: {{ $row->lat_masuk_lembur ?? '-' }}<br>
                          Lng: {{ $row->lng_masuk_lembur ?? '-' }}<br>
                          Akurasi: {{ $row->accuracy_masuk_lembur ?? '-' }}<br>
                          Alamat: {{ $row->address_masuk_lembur ?? '-' }}<br>
                          @if($row->lat_masuk_lembur && $row->lng_masuk_lembur)
                            <a href="https://www.google.com/maps?q={{ $row->lat_masuk_lembur }},{{ $row->lng_masuk_lembur }}" target="_blank">Buka Lokasi</a>
                          @endif
                          @if($row->photo_path_masuk_lembur)
                            <br>
                            <a href="{{ Storage::url($row->photo_path_masuk_lembur) }}" target="_blank">Lihat Foto</a>
                          @endif
                        </div>
                      @endif

                      @if($row->pulang_lembur)
                        <div class="ket-block" style="margin-top:10px;">
                          <b>Pulang Lembur</b><br>
                          Lat: {{ $row->lat_pulang_lembur ?? '-' }}<br>
                          Lng: {{ $row->lng_pulang_lembur ?? '-' }}<br>
                          Akurasi: {{ $row->accuracy_pulang_lembur ?? '-' }}<br>
                          Alamat: {{ $row->address_pulang_lembur ?? '-' }}<br>
                          @if($row->lat_pulang_lembur && $row->lng_pulang_lembur)
                            <a href="https://www.google.com/maps?q={{ $row->lat_pulang_lembur }},{{ $row->lng_pulang_lembur }}" target="_blank">Buka Lokasi</a>
                          @endif
                          @if($row->photo_path_pulang_lembur)
                            <br>
                            <a href="{{ Storage::url($row->photo_path_pulang_lembur) }}" target="_blank">Lihat Foto</a>
                          @endif
                        </div>
                      @endif
                    </div>
                  </td>
                  <td>
                    @if($row->is_approved)
                      <span class="status-approved">Approved</span>
                      <div class="approval-meta">
                        oleh {{ optional($row->approvedBy)->name ?? '-' }}<br>
                        {{ \Carbon\Carbon::parse($row->approved_at)->format('d-m-Y H:i') }}
                      </div>
                    @else
                      <span class="status-pending">Pending</span>
                    @endif
                  </td>
                  @can('admin')
                    <td>
                      @if(!$row->is_approved)
                        <form action="{{ route('absensi.approve', $row->id) }}" method="POST" onsubmit="return confirm('Setujui absensi ini?');">
                          @csrf
                          <button type="submit" class="approve-btn">Approve</button>
                        </form>
                      @else
                        <span class="approve-disabled">-</span>
                      @endif
                    </td>
                  @endcan
                </tr>
              @empty
                <tr>
                  @can('admin')
                    <td colspan="11" class="empty-cell">Belum ada data.</td>
                  @else
                    <td colspan="10" class="empty-cell">Belum ada data.</td>
                  @endcan
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</div>
@endsection
<script>
  function toggleKet(button) {
    const wrapper = button.nextElementSibling;
    const isOpen = wrapper.style.display === 'block';

    wrapper.style.display = isOpen ? 'none' : 'block';
    button.textContent = isOpen ? 'Lihat Detail' : 'Sembunyikan Detail';
  }
</script>