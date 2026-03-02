@extends('mobile.layout')

@section('title', 'Detail Kasbon')
@section('header', 'Detail Kasbon')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border p-4 space-y-3">

        <div class="flex justify-between items-start">
            <div>
                <div class="text-xs text-gray-500">ID Pengajuan</div>
                <div class="text-lg font-bold">#{{ $kasbon->id }}</div>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold
                {{ str_contains($kasbon->status_awal,'waiting') ? 'bg-yellow-100 text-yellow-800' : '' }}
                {{ str_contains($kasbon->status_awal,'approved') ? 'bg-green-100 text-green-800' : '' }}
                {{ str_contains($kasbon->status_awal,'rejected') ? 'bg-red-100 text-red-800' : '' }}">
                {{ $kasbon->status_awal }}
            </span>
        </div>

        <div class="border-t pt-3 text-sm space-y-2">
            <div><b>Tanggal:</b> {{ $kasbon->tanggal_pengajuan->format('d M Y') }}</div>
            <div><b>Nominal:</b> Rp {{ number_format($kasbon->nominal,0,',','.') }}</div>
            <div><b>Tenor:</b> {{ $kasbon->tenor }}x</div>
            <div><b>Cicilan:</b> Rp {{ number_format($kasbon->cicilan,0,',','.') }}</div>
        </div>

        <div class="border-t pt-3 text-sm">
            <b>Alasan:</b>
            <div class="text-gray-600 mt-1">{{ $kasbon->alasan_pengajuan }}</div>
        </div>

        <div class="border-t pt-3 text-sm space-y-2">
            <div><b>Prioritas:</b> {{ $kasbon->prioritas ?? '-' }}</div>
            <div><b>Catatan Staff:</b> {{ $kasbon->catatan_staff ?? '-' }}</div>
        </div>
    </div>

    <a href="{{ route('m.kasbon.index') }}"
       class="block w-full text-center py-3 rounded-2xl bg-gray-200 font-semibold mt-4">
        Kembali
    </a>
@endsection
