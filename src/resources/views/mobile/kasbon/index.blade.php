@extends('mobile.layout')

@section('title', 'Kasbon Saya')
@section('header', 'Kasbon Saya')

@section('headerAction')
    <a href="{{ route('m.kasbon.create') }}"
       class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-2 rounded-xl">
        Ajukan
    </a>
@endsection

@section('content')
    <div class="space-y-3">

        @forelse($requests as $r)
            <a href="{{ route('m.kasbon.show', $r->id) }}"
               class="block rounded-2xl border border-slate-700 bg-slate-800/60 p-4">

                <div class="flex justify-between items-start">
                    <div>
                        <div class="font-bold text-base text-slate-100">
                            Rp {{ number_format($r->nominal,0,',','.') }}
                        </div>
                        <div class="text-xs text-slate-400">
                            {{ $r->tanggal_pengajuan->format('d M Y') }}
                        </div>
                    </div>

                    @php
                        $status = strtolower($r->status_awal);
                        $badge = 'bg-slate-700 text-slate-100 border border-slate-600';
                        if (str_contains($status, 'waiting')) $badge = 'bg-amber-500/15 text-amber-200 border border-amber-500/30';
                        if (str_contains($status, 'approved')) $badge = 'bg-emerald-500/15 text-emerald-200 border border-emerald-500/30';
                        if (str_contains($status, 'rejected')) $badge = 'bg-rose-500/15 text-rose-200 border border-rose-500/30';
                    @endphp

                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                        {{ $r->status_awal }}
                    </span>
                </div>

                <div class="text-sm mt-2 text-slate-200">
                    Tenor: <b>{{ $r->tenor }}x</b> • Cicilan:
                    <b>Rp {{ number_format($r->cicilan,0,',','.') }}</b>
                </div>

                <div class="text-xs text-slate-400 mt-2 line-clamp-2">
                    {{ $r->alasan_pengajuan }}
                </div>

            </a>
        @empty
            <div class="text-center text-slate-400 mt-12">
                Belum ada pengajuan kasbon.
            </div>
        @endforelse
    </div>
@endsection