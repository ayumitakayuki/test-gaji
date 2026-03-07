@extends('mobile.layout')

@section('title', 'Ajukan Kasbon')
@section('header', 'Ajukan Kasbon')

@section('content')
    <form method="POST" action="{{ route('m.kasbon.store') }}" class="space-y-4">
        @csrf

        <div class="rounded-2xl border border-slate-700 bg-slate-800/60 p-4 space-y-3">

            <div>
                <label class="text-sm font-semibold text-slate-200">Nominal</label>
                <input type="number" name="nominal" value="{{ old('nominal') }}"
                       class="w-full mt-1 rounded-xl px-4 py-3 bg-slate-900/60 border border-slate-700 text-slate-100 placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/40"
                       placeholder="contoh: 500000" required>
                @error('nominal') <p class="text-rose-300 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-200">Tenor (X kali)</label>
                <input type="number" name="tenor" value="{{ old('tenor', 1) }}"
                       class="w-full mt-1 rounded-xl px-4 py-3 bg-slate-900/60 border border-slate-700 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40"
                       required min="1" max="12">
                @error('tenor') <p class="text-rose-300 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-200">Alasan Pengajuan</label>
                <textarea name="alasan_pengajuan"
                          class="w-full mt-1 rounded-xl px-4 py-3 bg-slate-900/60 border border-slate-700 text-slate-100 placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/40"
                          rows="4" required>{{ old('alasan_pengajuan') }}</textarea>
                @error('alasan_pengajuan') <p class="text-rose-300 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-2xl font-semibold">
            Kirim Pengajuan
        </button>

        <a href="{{ route('m.kasbon.index') }}"
           class="block w-full text-center py-3 rounded-2xl bg-slate-700/60 border border-slate-600 text-slate-100 font-semibold">
            Batal
        </a>
    </form>
@endsection