@extends('mobile.layout')

@section('content')
<div style="max-width:480px;margin:0 auto;padding:16px;">
  <h2>Absensi (Selfie Proof)</h2>

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
      <!-- Tambahkan baris untuk alamat -->
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

  <div id="msg" style="margin-top:12px;white-space:pre-wrap;"></div>
</div>

<script>
(() => {
  const video = document.getElementById('video');
  const canvas = document.getElementById('canvas');

  const camStatus = document.getElementById('camStatus');
  const locStatus = document.getElementById('locStatus');

  const latEl = document.getElementById('lat');
  const lngEl = document.getElementById('lng');
  const accEl = document.getElementById('acc');
  const locTimeEl = document.getElementById('locTime');
  const addressEl = document.getElementById('address'); // elemen baru untuk alamat
  const msgEl = document.getElementById('msg');

  const btnStart = document.getElementById('btnStart');
  const btnStop = document.getElementById('btnStop');
  const btnAbsen = document.getElementById('btnAbsen');

  setMsg(
    `protocol: ${location.protocol}\n` +
    `origin: ${location.origin}\n` +
    `secureContext: ${window.isSecureContext}\n`
  );
  setMsg(`protocol=${location.protocol} secure=${window.isSecureContext}`);
  
  let stream = null;
  let watchId = null;

  let lastPos = null; // {lat,lng,acc,ts}

  function setMsg(t) { msgEl.textContent = t; }
  function nowStr() { return new Date().toLocaleString(); }

  async function startCamera() {
    try {
      camStatus.textContent = 'Requesting...';

      if (!window.isSecureContext) {
        throw new Error('Insecure context. Pastikan akses via HTTPS (trycloudflare) dan tidak mixed content.');
      }

      stream = await navigator.mediaDevices.getUserMedia({
        video: { facingMode: "user", width: { ideal: 480 }, height: { ideal: 360 } },
        audio: false
      });

      video.srcObject = stream;
      camStatus.textContent = 'ON';
    } catch (e) {
      camStatus.textContent = 'FAILED';
      setMsg(`Gagal akses kamera: ${e.name || ''} ${e.message || e}`);
    }
  }

  function stopCamera() {
    if (stream) {
      stream.getTracks().forEach(t => t.stop());
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

    // watchPosition = lokasi terbaca live (update berkala)
    watchId = navigator.geolocation.watchPosition(
      (pos) => {
        const { latitude, longitude, accuracy } = pos.coords;
        lastPos = { lat: latitude, lng: longitude, acc: accuracy, ts: Date.now() };

        locStatus.textContent = 'ON';
        latEl.textContent = latitude.toFixed(6);
        lngEl.textContent = longitude.toFixed(6);
        accEl.textContent = Math.round(accuracy);
        locTimeEl.textContent = nowStr();

        // Reverse geocoding untuk menampilkan alamat (misal via Nominatim)
        // Hasil geocoding di-set ke <span id="address">
        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json`)
          .then(response => response.json())
          .then(data => {
            // Pilih field yang paling spesifik: desa/village, kota/city, county/district, dll.
            const addr = data.address || {};
            const place = addr.village || addr.suburb || addr.city ||
                          addr.county || addr.town || addr.district ||
                          addr.state || addr.region;
            addressEl.textContent = place || data.display_name || '-';
          })
          .catch(() => {
            addressEl.textContent = 'Tidak ditemukan';
          });
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
    // pastikan video ready
    const vw = video.videoWidth;
    const vh = video.videoHeight;
    if (!vw || !vh) throw new Error('Video belum siap');

    // kecilkan lagi buat hemat size & device
    const targetW = 480;
    const scale = Math.min(1, targetW / vw);
    const w = Math.round(vw * scale);
    const h = Math.round(vh * scale);

    canvas.width = w;
    canvas.height = h;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, w, h);

    // hasil base64 jpeg
    return canvas.toDataURL('image/jpeg', quality);
  }

  async function submitAttendance() {
    try {
      if (!stream) throw new Error('Kamera belum ON');
      if (!lastPos) throw new Error('Lokasi belum terbaca. Pastikan izin lokasi ON.');

      // optional: cek akurasi minimal (misal <= 100m)
      if (lastPos.acc > 150) {
        throw new Error('Akurasi lokasi terlalu besar (' + Math.round(lastPos.acc) + 'm). Coba tunggu sampai lebih akurat.');
      }

      const image_base64 = captureJpegBase64(0.7);

      btnAbsen.disabled = true;
      setMsg('Mengirim absensi...');

      const res = await fetch("{{ route('m.absensi.check') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': "{{ csrf_token() }}",
        },
        body: JSON.stringify({
          // kamu bisa ganti type sesuai kebutuhan
          type: 'masuk_pagi',
          image_base64,
          lat: lastPos.lat,
          lng: lastPos.lng,
          accuracy: lastPos.acc,
          captured_at: new Date().toISOString(),
          // opsional: kirim alamat jika ingin disimpan di backend
          address: addressEl.textContent
        })
      });

      const data = await res.json().catch(() => ({}));
      if (!res.ok) {
        throw new Error(data.message || 'Gagal submit absensi');
      }
      setMsg('✅ Berhasil absen!\n' +
           JSON.stringify(data, null, 2) +
           '\nAlamat: ' + addressEl.textContent);

      // optional: stop camera/location setelah sukses (hemat baterai)
      // stopCamera(); stopLocation();

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