@extends('mobile.layout')

@section('title', 'Ajukan Kasbon')
@section('header', 'Ajukan Kasbon')

@section('content')
    <form method="POST" action="{{ route('m.kasbon.store') }}" class="space-y-4">
        @csrf

        <div class="bg-white rounded-2xl shadow-sm border p-4 space-y-3">

            <div>
                <label class="text-sm font-semibold">Nominal</label>
                <input type="number" name="nominal" value="{{ old('nominal') }}"
                       class="w-full mt-1 border rounded-xl px-4 py-3"
                       placeholder="contoh: 500000" required>
                @error('nominal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold">Tenor (X kali)</label>
                <input type="number" name="tenor" value="{{ old('tenor', 1) }}"
                       class="w-full mt-1 border rounded-xl px-4 py-3"
                       required min="1" max="12">
                @error('tenor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold">Alasan Pengajuan</label>
                <textarea name="alasan_pengajuan"
                          class="w-full mt-1 border rounded-xl px-4 py-3"
                          rows="4" required>{{ old('alasan_pengajuan') }}</textarea>
                @error('alasan_pengajuan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 text-white py-3 rounded-2xl font-semibold">
            Kirim Pengajuan
        </button>

        <a href="{{ route('m.kasbon.index') }}"
           class="block w-full text-center py-3 rounded-2xl bg-gray-200 font-semibold">
            Batal
        </a>
    </form>
@endsection
