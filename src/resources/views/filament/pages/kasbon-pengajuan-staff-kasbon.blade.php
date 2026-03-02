<x-filament-panels::page>
    <form wire:submit.prevent="saveDraft">
        {{ $this->form }}

        <div class="flex gap-2 mt-6">
            <x-filament::button type="submit" color="gray">
                Simpan Draft
            </x-filament::button>

            <x-filament::button type="button" color="success" wire:click="submitToDO">
                Kirim ke DO
            </x-filament::button>

            <x-filament::button type="button" color="danger"
                onclick="window.location='{{ route('filament.admin.pages.kasbon-pinjaman-staff-kasbon') }}'">
                Batal
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
