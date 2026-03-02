<?php

namespace App\Filament\Admin\Pages;

use App\Models\KasbonRequest;
use App\Models\KasbonLoan;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class KasbonDetailDO extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Detail Kasbon (DO)';
    protected static ?string $title           = 'Detail Pengajuan Kasbon';
    protected static ?string $navigationGroup = 'Direktur Operasional';

    protected static string $view = 'filament.pages.kasbon-detail-do';

    public ?KasbonRequest $record = null;
    public string $tab = 'awal'; // awal / akhir

    public static function canAccess(): bool
    {
        return Gate::allows('kasbon.approve');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false; // detail tidak muncul menu
    }

    public function mount(): void
    {
        $id = request('id');
        $this->tab = request('tab', 'awal');

        $this->record = KasbonRequest::with(['karyawan'])->findOrFail($id);
    }

    // =========================
    // Action Approve / Reject
    // =========================

    public function approveAwal(): void
    {
        if ($this->record->status_awal !== 'waiting_do_awal') {
            Notification::make()->title('Data sudah diproses')->warning()->send();
            return;
        }

        $this->record->update([
            'status_awal'       => 'approved_do_awal',
            'status_akhir'      => 'waiting_staff_akhir',
            'approved_awal_by'  => Auth::id(),
            'approved_awal_at'  => now(),
        ]);

        Notification::make()->title('Approve Tahap Awal berhasil')->success()->send();
        $this->redirect(route('filament.admin.pages.kasbon-verifikasi-awal-d-o'));
    }

    public function rejectAwal(): void
    {
        if ($this->record->status_awal !== 'waiting_do_awal') {
            Notification::make()->title('Data sudah diproses')->warning()->send();
            return;
        }

        $this->record->update([
            'status_awal'       => 'rejected_do_awal',
            'approved_awal_by'  => Auth::id(),
            'approved_awal_at'  => now(),
        ]);

        Notification::make()->title('Reject Tahap Awal berhasil')->danger()->send();
        $this->redirect(route('filament.admin.pages.kasbon-verifikasi-awal-d-o'));
    }

    public function approveFinal(): void
    {
        if ($this->record->status_akhir !== 'waiting_do_akhir') {
            Notification::make()->title('Data belum siap untuk approval final')->warning()->send();
            return;
        }

        // ✅ generate KasbonLoan otomatis
        $loan = KasbonLoan::create([
            'karyawan_id' => $this->record->karyawan_id,
            'tanggal'     => now(),
            'pokok'       => $this->record->nominal,
            'tenor'       => $this->record->tenor,
            'cicilan'     => $this->record->cicilan,
            'sisa_kali'   => $this->record->tenor,
            'sisa_saldo'  => $this->record->nominal,
            'status'      => 'aktif',
            'keterangan'  => $this->record->alasan_pengajuan,
        ]);

        $this->record->update([
            'status_akhir'      => 'approved_do_akhir',
            'approved_akhir_by' => Auth::id(),
            'approved_akhir_at' => now(),
            'kasbon_loan_id'    => $loan->id,
        ]);

        Notification::make()->title('Approve Final berhasil — Kasbon Loan dibuat')->success()->send();
        $this->redirect(route('filament.admin.pages.kasbon-verifikasi-akhir-d-o'));
    }

    public function rejectFinal(): void
    {
        if ($this->record->status_akhir !== 'waiting_do_akhir') {
            Notification::make()->title('Data belum siap untuk reject final')->warning()->send();
            return;
        }

        $this->record->update([
            'status_akhir'      => 'rejected_do_akhir',
            'approved_akhir_by' => Auth::id(),
            'approved_akhir_at' => now(),
        ]);

        Notification::make()->title('Reject Final berhasil')->danger()->send();
        $this->redirect(route('filament.admin.pages.kasbon-verifikasi-akhir-d-o'));
    }
}
