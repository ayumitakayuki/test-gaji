@extends('mobile.layout')

@section('title', 'Ajukan Perizinan')
@section('header', 'Ajukan Perizinan')

@section('content')
    <form method="POST" action="{{ route('m.perizinan.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div class="rounded-2xl border border-slate-700 bg-slate-800/60 p-4 space-y-3">

            <div>
                <label class="text-sm font-semibold text-slate-200">Jenis Izin</label>
                <select name="jenis"
                        class="w-full mt-1 rounded-xl px-4 py-3 bg-slate-900/60 border border-slate-700 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40"
                        required>
                    <option value="sakit" {{ old('jenis') == 'sakit' ? 'selected' : '' }}>Sakit (Surat Dokter)</option>
                    <option value="izin" {{ old('jenis') == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="cuti" {{ old('jenis') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                    <option value="berduka" {{ old('jenis') == 'berduka' ? 'selected' : '' }}>Berduka</option>
                    <option value="tanpa_alasan" {{ old('jenis') == 'tanpa_alasan' ? 'selected' : '' }}>Tanpa Alasan</option>
                </select>
                @error('jenis') <p class="text-rose-300 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-200">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                       class="w-full mt-1 rounded-xl px-4 py-3 bg-slate-900/60 border border-slate-700 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40"
                       required>
                @error('tanggal_mulai') <p class="text-rose-300 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-200">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                       class="w-full mt-1 rounded-xl px-4 py-3 bg-slate-900/60 border border-slate-700 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40"
                       required>
                @error('tanggal_selesai') <p class="text-rose-300 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-200">Keterangan</label>
                <textarea name="keterangan"
                          class="w-full mt-1 rounded-xl px-4 py-3 bg-slate-900/60 border border-slate-700 text-slate-100 placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/40"
                          rows="4">{{ old('keterangan') }}</textarea>
                @error('keterangan') <p class="text-rose-300 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-200">Bukti (opsional)</label>
                <input type="file" name="bukti_path" accept="image/*,application/pdf"
                       class="w-full mt-1 rounded-xl px-4 py-3 bg-slate-900/60 border border-slate-700 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
                @error('bukti_path') <p class="text-rose-300 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-2xl font-semibold">
            Ajukan Izin
        </button>

        <a href="{{ route('m.perizinan.index') }}"
           class="block w-full text-center py-3 rounded-2xl bg-slate-700/60 border border-slate-600 text-slate-100 font-semibold">
            Batal
        </a>
    </form>
@endsection