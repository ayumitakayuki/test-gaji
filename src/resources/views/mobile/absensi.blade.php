@extends('mobile.layout')

@section('content')
<div style="max-width:480px;margin:0 auto;padding:16px;">
  <h2>Absensi (Selfie Proof)</h2>

  <div style="margin-top:12px;">
    <label for="type" style="display:block;margin-bottom:6px;font-weight:600;">Pilih Waktu Absensi</label>
    <select id="type" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:12px;">
      <option value="masuk_pagi">Masuk Pagi</option>
      <option value="keluar_siang">Keluar Siang</option>
      <option value="masuk_siang">Masuk Siang</option>
      <option value="pulang_kerja">Pulang Kerja</option>
      <option value="masuk_lembur">Masuk Lembur</option>
      <option value="pulang_lembur">Pulang Lembur</option>
    </select>
  </div>

  <div style="margin-top:12px;">
    <video id="video" playsinline autoplay muted style="width:100%;border-radius:12px;background:#000;"></video>
    <canvas id="canvas" style="display:none;"></canvas>
  </div>

  <div style="margin-top:12px;padding:12px;border:1px solid #ddd;border-radius:12px;">
    <div><b>Status Kamera:</b> <span id="camStatus">-</span></div>
    <div><b>Status Lokasi:</b> <span id="locStatus">-</span></div>
    <div style="margin-top:6px;font-size:14px;">
      <div>Lat: <span id="lat">-</span></div>
      <div>Lng: <span id="lng">-</span></div>
      <div>Akurasi: <span id="acc">-</span> m</div>
      <div>Update: <span id="locTime">-</span></div>
      <div>Alamat: <span id="address">-</span></div>
    </div>
  </div>

  <div style="display:flex;gap:8px;margin-top:12px;">
    <button id="btnStart" type="button" style="flex:1;padding:12px;border-radius:12px;">Start</button>
    <button id="btnStop" type="button" style="flex:1;padding:12px;border-radius:12px;">Stop</button>
  </div>

  <button id="btnAbsen" type="button"
    style="width:100%;margin-top:12px;padding:14px;border-radius:12px;font-weight:700;">
    Absen Sekarang
  </button>
  <div style="margin-top:12px;">
    <a href="{{ route('m.absensi.history') }}">Lihat Riwayat Absensi</a>
  </div>
  {{-- <div style="margin-top:8px;">
    <a href="{{ route('m.perizinan.index') }}">Perizinan</a>
  </div> --}}
  <div id="msg" style="margin-top:12px;white-space:pre-wrap;"></div>
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