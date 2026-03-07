@extends('mobile.layout')

@section('title', 'Detail Kasbon')
@section('header', 'Detail Kasbon')

@section('content')
    <div class="rounded-2xl border border-slate-700 bg-slate-800/60 p-4 space-y-3">

        <div class="flex justify-between items-start">
            <div>
                <div class="text-xs text-slate-400">ID Pengajuan</div>
                <div class="text-lg font-bold text-slate-100">#{{ $kasbon->id }}</div>
            </div>

            @php
                $status = strtolower($kasbon->status_awal);
                $badge = 'bg-slate-700 text-slate-100 border border-slate-600';
                if (str_contains($status, 'waiting')) $badge = 'bg-amber-500/15 text-amber-200 border border-amber-500/30';
                if (str_contains($status, 'approved')) $badge = 'bg-emerald-500/15 text-emerald-200 border border-emerald-500/30';
                if (str_contains($status, 'rejected')) $badge = 'bg-rose-500/15 text-rose-200 border border-rose-500/30';
            @endphp

            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                {{ $kasbon->status_awal }}
            </span>
        </div>

        <div class="border-t border-slate-700 pt-3 text-sm space-y-2 text-slate-200">
            <div><b>Tanggal:</b> {{ $kasbon->tanggal_pengajuan->format('d M Y') }}</div>
            <div><b>Nominal:</b> Rp {{ number_format($kasbon->nominal,0,',','.') }}</div>
            <div><b>Tenor:</b> {{ $kasbon->tenor }}x</div>
            <div><b>Cicilan:</b> Rp {{ number_format($kasbon->cicilan,0,',','.') }}</div>
        </div>

        <div class="border-t border-slate-700 pt-3 text-sm">
            <b class="text-slate-200">Alasan:</b>
            <div class="text-slate-400 mt-1">{{ $kasbon->alasan_pengajuan }}</div>
        </div>

        <div class="border-t border-slate-700 pt-3 text-sm space-y-2 text-slate-200">
            <div><b>Prioritas:</b> {{ $kasbon->prioritas ?? '-' }}</div>
            <div><b>Catatan Staff:</b> <span class="text-slate-400">{{ $kasbon->catatan_staff ?? '-' }}</span></div>
        </div>
    </div>

    <a href="{{ route('m.kasbon.index') }}"
       class="block w-full text-center py-3 rounded-2xl bg-slate-700/60 border border-slate-600 text-slate-100 font-semibold mt-4">
        Kembali
    </a>
@endsection