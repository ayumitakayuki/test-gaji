<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Gate;

class RekapHoCenter extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $title = 'Rekap HO';
    protected static string $view = 'filament.pages.rekap-ho-center';
    protected static ?int $navigationSort = 8;

    public static function getNavigationGroup(): ?string
    {
        return 'Penggajian';
    }

   public static function canAccess(): bool
    {
        return Gate::allows('penggajian.process')
            || Gate::allows('absensi.validate')
            || Gate::allows('karyawan.manage');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

}
