<x-filament-panels::page>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- ✅ SECTION: Data Pengajuan --}}
        <x-filament::section heading="Data Pengajuan">
            <div class="space-y-2 text-sm">
                <div><b>ID Pengajuan:</b> {{ $record->id }}</div>
                <div><b>Tanggal:</b> {{ optional($record->tanggal_pengajuan)->format('d-m-Y') }}</div>
                <div><b>Status Awal:</b> <span class="font-semibold">{{ $record->status_awal }}</span></div>
                <div><b>Status Akhir:</b> <span class="font-semibold">{{ $record->status_akhir }}</span></div>
            </div>
        </x-filament::section>

        {{-- ✅ SECTION: Data Karyawan --}}
        <x-filament::section heading="Data Karyawan">
            <div class="space-y-2 text-sm">
                <div><b>Nama:</b> {{ $record->karyawan?->nama ?? '-' }}</div>
                <div><b>ID Karyawan:</b> {{ $record->karyawan_id }}</div>
            </div>
        </x-filament::section>

        {{-- ✅ SECTION: Detail Kasbon --}}
        <x-filament::section heading="Detail Kasbon">
            <div class="space-y-2 text-sm">
                <div><b>Nominal:</b> Rp {{ number_format($record->nominal,0,',','.') }}</div>
                <div><b>Tenor:</b> {{ $record->tenor }} x</div>
                <div><b>Cicilan:</b> Rp {{ number_format($record->cicilan,0,',','.') }}</div>
                <div class="mt-3">
                    <b>Alasan:</b>
                    <div class="text-gray-600">{{ $record->alasan_pengajuan ?? '-' }}</div>
                </div>
            </div>
        </x-filament::section>

        {{-- ✅ SECTION: Catatan Staff --}}
        <x-filament::section heading="Verifikasi Staff Kasbon">
            <div class="space-y-2 text-sm">
                <div><b>Prioritas:</b> {{ $record->prioritas ?? '-' }}</div>
                <div><b>Catatan Staff:</b> {{ $record->catatan_staff ?? '-' }}</div>
                <div><b>Verified At:</b> {{ optional($record->verified_at)?->format('d-m-Y H:i') ?? '-' }}</div>
            </div>
        </x-filament::section>
    </div>

    {{-- ✅ SECTION: ACTION BUTTONS (DO) --}}
    <div class="mt-6">
        <x-filament::section heading="Keputusan Direktur Operasional">
            <div class="flex gap-3 flex-wrap">

                {{-- Tahap awal --}}
                @if($tab === 'awal' && $record->status_awal === 'waiting_do_awal')
                    <x-filament::button color="success" wire:click="approveAwal">
                        Approve Tahap Awal
                    </x-filament::button>

                    <x-filament::button color="danger" wire:click="rejectAwal">
                        Reject Tahap Awal
                    </x-filament::button>
                @endif

                {{-- Tahap akhir --}}
                @if($tab === 'akhir' && $record->status_akhir === 'waiting_do_akhir')
                    <x-filament::button color="success" wire:click="approveFinal">
                        Approve Final
                    </x-filament::button>

                    <x-filament::button color="danger" wire:click="rejectFinal">
                        Reject Final
                    </x-filament::button>
                @endif

                <x-filament::button
                    color="gray"
                    onclick="history.back()">
                    Kembali
                </x-filament::button>

            </div>
        </x-filament::section>
    </div>

</x-filament-panels::page>
