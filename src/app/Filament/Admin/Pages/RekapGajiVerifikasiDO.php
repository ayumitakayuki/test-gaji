<?php
// app/Filament/Admin/Pages/RekapGajiVerifikasiDO.php
namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Gate;

class RekapGajiVerifikasiDO extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-check-circle';
    protected static ?string $title           = 'Verifikasi Rekap Gaji';
    protected static ?string $navigationGroup = 'Direktur Operasional';
    protected static string $view             = 'filament.pages.rekap-gaji-verifikasi-do';

    public static function canAccess(): bool
    {
        return Gate::allows('penggajian.approve');
    }
}