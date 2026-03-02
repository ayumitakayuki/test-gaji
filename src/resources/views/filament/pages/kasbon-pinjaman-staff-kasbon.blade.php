<x-filament-panels::page>

    <div class="flex gap-2 mb-4">
        <a href="{{ request()->fullUrlWithQuery(['tab' => 'awal']) }}"
           class="px-4 py-2 rounded-md text-sm font-medium
           {{ request('tab','awal') === 'awal' ? 'bg-primary-600 text-white' : 'bg-gray-100' }}">
            Verifikasi Awal
        </a>

        <a href="{{ request()->fullUrlWithQuery(['tab' => 'akhir']) }}"
           class="px-4 py-2 rounded-md text-sm font-medium
           {{ request('tab') === 'akhir' ? 'bg-primary-600 text-white' : 'bg-gray-100' }}">
            Verifikasi Akhir
        </a>
    </div>

    {{ $this->table }}

</x-filament-panels::page>
