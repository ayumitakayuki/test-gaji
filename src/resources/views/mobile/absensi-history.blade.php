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
          <th class="border px-2 py-1">Bukti Foto</th>
          <th class="border px-2 py-1">Status Approval</th>
          @can('admin')
            <th class="border px-2 py-1">Aksi</th>
          @endcan
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
            <td class="border px-2 py-1">
              @if(!empty($row->bukti_foto))
                <a href="{{ Storage::url($row->bukti_foto) }}" target="_blank">
                  <img src="{{ Storage::url($row->bukti_foto) }}" alt="Bukti Foto" class="h-16 w-16 object-cover">
                </a>
              @else
                -
              @endif
            </td>
            <td class="border px-2 py-1">
              @if($row->is_approved)
                <span class="text-green-600">Approved</span>
                <div class="text-xs text-gray-500">
                  oleh {{ optional($row->approvedBy)->name ?? '-' }}<br>
                  {{ \Carbon\Carbon::parse($row->approved_at)->format('d-m-Y H:i') }}
                </div>
              @else
                <span class="text-yellow-600">Pending</span>
              @endif
            </td>
            @can('admin')
              <td class="border px-2 py-1">
                @if(!$row->is_approved)
                  <form action="{{ route('absensi.approve', $row->id) }}" method="POST" onsubmit="return confirm('Setujui absensi ini?');">
                    @csrf
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs">Approve</button>
                  </form>
                @else
                  <span class="text-gray-400">-</span>
                @endif
              </td>
            @endcan
          </tr>
        @empty
          <tr>
            @can('admin')
              <td colspan="10" class="border text-center px-2 py-1">Belum ada data.</td>
            @else
              <td colspan="9" class="border text-center px-2 py-1">Belum ada data.</td>
            @endcan
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection