@extends('mobile.layout')

@section('title', 'Kasbon Saya')
@section('header', 'Kasbon Saya')

@section('headerAction')
    <a href="{{ route('m.kasbon.create') }}"
       class="bg-blue-600 text-white text-sm px-3 py-2 rounded-xl">
        Ajukan
    </a>
@endsection

@section('content')
    <div class="space-y-3">

        @forelse($requests as $r)
            <a href="{{ route('m.kasbon.show', $r->id) }}"
               class="block bg-white rounded-2xl shadow-sm border p-4">

                <div class="flex justify-between items-start">
                    <div>
                        <div class="font-bold text-base">
                            Rp {{ number_format($r->nominal,0,',','.') }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $r->tanggal_pengajuan->format('d M Y') }}
                        </div>
                    </div>

                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ str_contains($r->status_awal,'waiting') ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ str_contains($r->status_awal,'approved') ? 'bg-green-100 text-green-800' : '' }}
                        {{ str_contains($r->status_awal,'rejected') ? 'bg-red-100 text-red-800' : '' }}">
                        {{ $r->status_awal }}
                    </span>
                </div>

                <div class="text-sm mt-2">
                    Tenor: <b>{{ $r->tenor }}x</b> • Cicilan:
                    <b>Rp {{ number_format($r->cicilan,0,',','.') }}</b>
                </div>

                <div class="text-xs text-gray-600 mt-2 line-clamp-2">
                    {{ $r->alasan_pengajuan }}
                </div>

            </a>
        @empty
            <div class="text-center text-gray-500 mt-12">
                Belum ada pengajuan kasbon.
            </div>
        @endforelse
    </div>
@endsection
