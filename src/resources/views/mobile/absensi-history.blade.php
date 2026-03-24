@extends('mobile.layout')

@section('header', 'Riwayat Absensi')

@section('content')
<div class="space-y-4">
  <h2 class="text-lg font-semibold">Riwayat Absensi</h2>
  <div class="overflow-x-auto">
    <table class="w-full border-collapse text-sm">
      <thead>
        <tr>
          <th class="border px-2 py-1">Tanggal</th>
          <th class="border px-2 py-1">Masuk Pagi</th>
          <th class="border px-2 py-1">Keluar Siang</th>
          <th class="border px-2 py-1">Masuk Siang</th>
          <th class="border px-2 py-1">Pulang Kerja</th>
          <th class="border px-2 py-1">Masuk Lembur</th>
          <th class="border px-2 py-1">Pulang Lembur</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($absensi as $row)
          <tr>
            <td class="border px-2 py-1">{{ \Carbon\Carbon::parse($row->tanggal)->format('d-m-Y') }}</td>
            <td class="border px-2 py-1">{{ $row->masuk_pagi ? \Carbon\Carbon::parse($row->masuk_pagi)->format('H:i') : '-' }}</td>
            <td class="border px-2 py-1">{{ $row->keluar_siang ? \Carbon\Carbon::parse($row->keluar_siang)->format('H:i') : '-' }}</td>
            <td class="border px-2 py-1">{{ $row->masuk_siang ? \Carbon\Carbon::parse($row->masuk_siang)->format('H:i') : '-' }}</td>
            <td class="border px-2 py-1">{{ $row->pulang_kerja ? \Carbon\Carbon::parse($row->pulang_kerja)->format('H:i') : '-' }}</td>
            <td class="border px-2 py-1">{{ $row->masuk_lembur ? \Carbon\Carbon::parse($row->masuk_lembur)->format('H:i') : '-' }}</td>
            <td class="border px-2 py-1">{{ $row->pulang_lembur ? \Carbon\Carbon::parse($row->pulang_lembur)->format('H:i') : '-' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="border text-center px-2 py-1">Belum ada data.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection