@extends('mobile.layout')

@section('title', 'Absensi')

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
    --warning-bg: rgba(245, 158, 11, 0.12);
    --warning-text: #92400e;
    --danger-bg: rgba(239, 68, 68, 0.12);
    --danger-text: #991b1b;
  }

  * {
    box-sizing: border-box;
  }

  html, body {
    overflow-x: hidden;
  }

  .absensi-mobile {
    min-height: 100vh;
    min-height: 100dvh;
    background: var(--bg);
    color: var(--text);
    font-family: Arial, Helvetica, sans-serif;
    width: 100%;
    overflow-x: hidden;
  }

  .absensi-header {
    background: var(--header-bg);
    padding: max(16px, env(safe-area-inset-top)) 14px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }

  .absensi-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
  }

  .absensi-brand {
    font-size: 18px;
    font-weight: 500;
    color: #111;
    white-space: nowrap;
  }

  .absensi-back {
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

  .absensi-back svg,
  .absensi-profile svg,
  .absensi-search-icon svg {
    width: 22px;
    height: 22px;
    display: block;
  }

  .absensi-header-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    justify-content: flex-end;
    min-width: 0;
  }

  .absensi-search {
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

  .absensi-search input {
    border: none;
    outline: none;
    background: transparent;
    width: 100%;
    min-width: 0;
    font-size: 14px;
    color: #111;
  }

  .absensi-search input::placeholder {
    color: #6b7280;
  }

  .absensi-profile {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #111;
    flex-shrink: 0;
  }

  .absensi-body {
    display: grid;
    grid-template-columns: 72px minmax(0, 1fr);
    min-height: calc(100vh - 70px);
    min-height: calc(100dvh - 70px);
    overflow-x: hidden;
  }

  .absensi-sidebar {
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

  .absensi-main {
    min-width: 0;
    padding: 16px 14px 22px;
    overflow-x: hidden;
  }

  .absensi-panel {
    background: var(--card-bg);
    border-radius: 22px;
    padding: 18px 14px 20px;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
  }

  .absensi-title {
    margin: 0 0 16px;
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    text-transform: uppercase;
  }

  .form-group {
    margin-bottom: 14px;
  }

  .form-label {
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: 700;
    color: #1f2937;
  }

  .form-select {
    width: 100%;
    min-height: 52px;
    border-radius: 15px;
    border: 1px solid var(--border);
    background: var(--input-bg);
    color: #111827;
    padding: 0 14px;
    font-size: 15px;
    outline: none;
    appearance: none;
  }

  .camera-card,
  .status-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 14px;
    margin-bottom: 14px;
  }

  .video-box {
    overflow: hidden;
    border-radius: 16px;
    background: #000;
    border: 1px solid var(--border);
  }

  #video {
    width: 100%;
    display: block;
    background: #000;
    aspect-ratio: 4 / 3;
    object-fit: cover;
  }

  .status-grid {
    display: grid;
    gap: 10px;
  }

  .status-row {
    font-size: 14px;
    line-height: 1.5;
    color: #374151;
  }

  .status-row b {
    color: #111827;
  }

  .status-pill {
    display: inline-flex;
    align-items: center;
    min-height: 28px;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    background: #eef2ff;
    color: #334155;
  }

  .button-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 12px;
  }

  .btn {
    min-height: 52px;
    border: none;
    border-radius: 16px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: transform 0.15s ease, background 0.2s ease, border-color 0.2s ease;
  }

  .btn:active {
    transform: scale(0.99);
  }

  .btn-secondary {
    background: #f8fafc;
    color: #374151;
    border: 1px solid var(--border);
  }

  .btn-secondary:hover {
    background: #eef2f7;
  }

  .btn-primary {
    width: 100%;
    background: var(--primary);
    color: #fff;
    margin-bottom: 12px;
  }

  .btn-primary:hover {
    background: var(--primary-dark);
  }

  .link-box {
    margin-top: 6px;
  }

  .link-box a {
    color: var(--primary-dark);
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
  }

  .link-box a:hover {
    text-decoration: underline;
  }

  #msg {
    margin-top: 14px;
    white-space: pre-wrap;
    background: #f8fafc;
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 14px;
    font-size: 14px;
    line-height: 1.6;
    color: #374151;
    min-height: 56px;
  }

  @media (max-width: 390px) {
    .absensi-body {
      grid-template-columns: 64px minmax(0, 1fr);
    }

    .absensi-sidebar {
      padding: 16px 8px 18px;
      gap: 22px;
    }

    .side-icon {
      width: 40px;
      height: 40px;
    }

    .absensi-main {
      padding: 14px 10px 18px;
    }

    .absensi-panel {
      padding: 16px 12px 18px;
    }

    .absensi-search {
      width: min(150px, 42vw);
    }

    .button-row {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="absensi-mobile">
  <div class="absensi-header">
    <div class="absensi-header-left">
      <div class="absensi-brand">RKU</div>

      <button type="button" class="absensi-back" aria-label="Kembali">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M15 18l-6-6 6-6"/>
        </svg>
      </button>
    </div>

    <div class="absensi-header-right">
      <div class="absensi-search">
        <span class="absensi-search-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="7"></circle>
            <path d="M20 20l-3.5-3.5"></path>
          </svg>
        </span>
        <input type="text" placeholder="Search">
      </div>

      <div class="absensi-profile" aria-label="Profile">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 5.5A1.5 1.5 0 0 1 5.5 4h13A1.5 1.5 0 0 1 20 5.5v13A1.5 1.5 0 0 1 18.5 20h-13A1.5 1.5 0 0 1 4 18.5v-13Z"/>
          <circle cx="12" cy="10" r="3"/>
          <path d="M7.5 17c1.2-2 3-3 4.5-3s3.3 1 4.5 3"/>
        </svg>
      </div>
    </div>
  </div>

  <div class="absensi-body">
    <aside class="absensi-sidebar">
      <a href="{{ route('m.dashboard') }}" class="side-icon" aria-label="Dashboard">
        <svg viewBox="0 0 24 24" fill="currentColor">
          <path d="M12 3l8 7v10a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1V10l8-7z"/>
        </svg>
      </a>

      <a href="{{ route('m.absensi.index') }}" class="side-icon active" aria-label="Absensi">
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

    <main class="absensi-main">
      <div class="absensi-panel">
        <h2 class="absensi-title">Absensi (Selfie Proof)</h2>

        <div class="form-group">
          <label for="type" class="form-label">Pilih Waktu Absensi</label>
          <select id="type" class="form-select">
            <option value="masuk_pagi">Masuk Pagi</option>
            <option value="keluar_siang">Keluar Siang</option>
            <option value="masuk_siang">Masuk Siang</option>
            <option value="pulang_kerja">Pulang Kerja</option>
            <option value="masuk_lembur">Masuk Lembur</option>
            <option value="pulang_lembur">Pulang Lembur</option>
          </select>
        </div>

        <div class="camera-card">
          <div class="video-box">
            <video id="video" playsinline autoplay muted></video>
          </div>
          <canvas id="canvas" style="display:none;"></canvas>
        </div>

        <div class="status-card">
          <div class="status-grid">
            <div class="status-row"><b>Status Kamera:</b> <span id="camStatus" class="status-pill">-</span></div>
            <div class="status-row"><b>Status Lokasi:</b> <span id="locStatus" class="status-pill">-</span></div>
            <div class="status-row"><b>Lat:</b> <span id="lat">-</span></div>
            <div class="status-row"><b>Lng:</b> <span id="lng">-</span></div>
            <div class="status-row"><b>Akurasi:</b> <span id="acc">-</span> m</div>
            <div class="status-row"><b>Update:</b> <span id="locTime">-</span></div>
            <div class="status-row"><b>Alamat:</b> <span id="address">-</span></div>
          </div>
        </div>

        <div class="button-row">
          <button id="btnStart" type="button" class="btn btn-secondary">Start</button>
          <button id="btnStop" type="button" class="btn btn-secondary">Stop</button>
        </div>

        <button id="btnAbsen" type="button" class="btn btn-primary">
          Absen Sekarang
        </button>

        <div class="link-box">
          <a href="{{ route('m.absensi.history') }}">Lihat Riwayat Absensi</a>
        </div>

        <div id="msg"></div>
      </div>
    </main>
  </div>
</div>

<script>
(() => {
  const video = document.getElementById('video');
  const canvas = document.getElementById('canvas');
  const typeEl = document.getElementById('type');

  const camStatus = document.getElementById('camStatus');
  const locStatus = document.getElementById('locStatus');

  const latEl = document.getElementById('lat');
  const lngEl = document.getElementById('lng');
  const accEl = document.getElementById('acc');
  const locTimeEl = document.getElementById('locTime');
  const addressEl = document.getElementById('address');
  const msgEl = document.getElementById('msg');

  const btnStart = document.getElementById('btnStart');
  const btnStop = document.getElementById('btnStop');
  const btnAbsen = document.getElementById('btnAbsen');

  let stream = null;
  let watchId = null;
  let lastPos = null;

  function setMsg(t) {
    msgEl.textContent = t;
  }

  function nowStr() {
    return new Date().toLocaleString();
  }

  async function startCamera() {
    try {
      camStatus.textContent = 'Requesting...';

      if (!window.isSecureContext) {
        throw new Error('Halaman harus dibuka lewat HTTPS.');
      }

      stream = await navigator.mediaDevices.getUserMedia({
        video: {
          facingMode: 'user',
          width: { ideal: 480 },
          height: { ideal: 360 }
        },
        audio: false
      });

      video.srcObject = stream;
      camStatus.textContent = 'ON';
    } catch (e) {
      camStatus.textContent = 'FAILED';
      setMsg('Gagal akses kamera: ' + (e.message || e));
    }
  }

  function stopCamera() {
    if (stream) {
      stream.getTracks().forEach(track => track.stop());
      stream = null;
      camStatus.textContent = 'OFF';
    }
  }

  function startLocation() {
    if (!navigator.geolocation) {
      locStatus.textContent = 'Not supported';
      return;
    }

    locStatus.textContent = 'Requesting...';

    watchId = navigator.geolocation.watchPosition(
      async (pos) => {
        const { latitude, longitude, accuracy } = pos.coords;

        lastPos = {
          lat: latitude,
          lng: longitude,
          acc: accuracy,
          ts: Date.now()
        };

        locStatus.textContent = 'ON';
        latEl.textContent = latitude.toFixed(6);
        lngEl.textContent = longitude.toFixed(6);
        accEl.textContent = Math.round(accuracy);
        locTimeEl.textContent = nowStr();

        try {
          const response = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json`);
          const data = await response.json();

          const addr = data.address || {};
          const place =
            addr.village ||
            addr.suburb ||
            addr.city ||
            addr.county ||
            addr.town ||
            addr.district ||
            addr.state ||
            data.display_name;

          addressEl.textContent = place || '-';
        } catch (e) {
          addressEl.textContent = 'Tidak ditemukan';
        }
      },
      (err) => {
        locStatus.textContent = 'FAILED';
        setMsg('Gagal akses lokasi: ' + err.message);
      },
      {
        enableHighAccuracy: true,
        maximumAge: 2000,
        timeout: 15000
      }
    );
  }

  function stopLocation() {
    if (watchId !== null) {
      navigator.geolocation.clearWatch(watchId);
      watchId = null;
      locStatus.textContent = 'OFF';
    }
  }

  function captureJpegBase64(quality = 0.7) {
    const vw = video.videoWidth;
    const vh = video.videoHeight;

    if (!vw || !vh) {
      throw new Error('Video belum siap');
    }

    const targetW = 480;
    const scale = Math.min(1, targetW / vw);
    const w = Math.round(vw * scale);
    const h = Math.round(vh * scale);

    canvas.width = w;
    canvas.height = h;

    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, w, h);

    return canvas.toDataURL('image/jpeg', quality);
  }

  async function submitAttendance() {
    try {
      const selectedType = typeEl.value;

      if (!selectedType) {
        throw new Error('Pilih jenis absensi terlebih dahulu.');
      }

      if (!stream) {
        throw new Error('Kamera belum ON');
      }

      if (!lastPos) {
        throw new Error('Lokasi belum terbaca. Pastikan izin lokasi aktif.');
      }

      if (lastPos.acc > 150) {
        throw new Error('Akurasi lokasi terlalu besar (' + Math.round(lastPos.acc) + 'm).');
      }

      const image_base64 = captureJpegBase64(0.7);

      btnAbsen.disabled = true;
      setMsg('Mengirim absensi...');

      const res = await fetch("{{ route('m.absensi.check', [], false) }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': "{{ csrf_token() }}",
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          type: selectedType,
          image_base64: image_base64,
          lat: lastPos.lat,
          lng: lastPos.lng,
          accuracy: lastPos.acc,
          captured_at: new Date().toISOString(),
          address: addressEl.textContent
        })
      });

      const data = await res.json().catch(() => ({}));

      if (!res.ok) {
        throw new Error(data.message || 'Gagal submit absensi');
      }

      setMsg(
        '✅ Berhasil absen\n' +
        'Jenis: ' + selectedType.replaceAll('_', ' ') + '\n' +
        'Jam: ' + (data.time || '-') + '\n' +
        'Lat: ' + lastPos.lat + '\n' +
        'Lng: ' + lastPos.lng + '\n' +
        'Akurasi: ' + Math.round(lastPos.acc) + ' m'
      );
    } catch (e) {
      setMsg('❌ ' + e.message);
    } finally {
      btnAbsen.disabled = false;
    }
  }

  btnStart.addEventListener('click', async () => {
    setMsg('');
    await startCamera();
    startLocation();
  });

  btnStop.addEventListener('click', () => {
    stopCamera();
    stopLocation();
  });

  btnAbsen.addEventListener('click', submitAttendance);
})();
</script>
@endsection