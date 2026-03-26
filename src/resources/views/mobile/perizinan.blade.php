@extends('mobile.layout')

@section('header', 'Riwayat Perizinan')

@section('content')
<div class="space-y-4">
  <h2 class="text-lg font-semibold">Riwayat Perizinan</h2>
  <div class="mb-4">
    <a href="{{ route('m.perizinan.create') }}"
       class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
      Ajukan Perizinan
    </a>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full border-collapse text-sm">
      <thead>
        <tr>
          <th class="border px-2 py-1">Jenis</th>
          <th class="border px-2 py-1">Keterangan</th>
          <th class="border px-2 py-1">Mulai</th>
          <th class="border px-2 py-1">Selesai</th>
          <th class="border px-2 py-1">Bukti</th>
          <th class="border px-2 py-1">Status</th>
          @can('admin')
            <th class="border px-2 py-1">Aksi</th>
          @endcan
        </tr>
      </thead>
      <tbody>
        @forelse ($perizinan as $izin)
          <tr>
            <td class="border px-2 py-1">
              {{ ucwords(str_replace('_', ' ', $izin->jenis)) }}
            </td>
            <td class="border px-2 py-1">
              {{ $izin->keterangan ?? '-' }}
            </td>
            <td class="border px-2 py-1">
              {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d-m-Y') }}
            </td>
            <td class="border px-2 py-1">
              {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d-m-Y') }}
            </td>
            <td class="border px-2 py-1">
              @if($izin->bukti_path)
                <a href="{{ Storage::url($izin->bukti_path) }}" target="_blank">
                  <img src="{{ Storage::url($izin->bukti_path) }}" alt="Bukti" class="h-16 w-16 object-cover">
                </a>
              @else
                -
              @endif
            </td>
            <td class="border px-2 py-1">
              @if($izin->is_approved)
                <span class="text-green-600">Approved</span>
                <div class="text-xs text-gray-500">
                  oleh {{ optional($izin->approvedBy)->name ?? '-' }}<br>
                  {{ \Carbon\Carbon::parse($izin->approved_at)->format('d-m-Y H:i') }}
                </div>
              @else
                <span class="text-yellow-600">Pending</span>
              @endif
            </td>
            @can('admin')
              <td class="border px-2 py-1">
                @if(! $izin->is_approved)
                  <form action="{{ route('perizinan.approve', $izin->id) }}"
                        method="POST"
                        onsubmit="return confirm('Setujui perizinan ini?');">
                    @csrf
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs">
                      Approve
                    </button>
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
              <td colspan="6" class="border text-center px-2 py-1">Belum ada data.</td>
            @else
              <td colspan="5" class="border text-center px-2 py-1">Belum ada data.</td>
            @endcan
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection