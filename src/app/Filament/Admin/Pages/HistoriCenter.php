<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Gate;

class HistoriCenter extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Histori Center';
    protected static ?string $title           = 'Histori Center';
    protected static ?string $navigationGroup = 'Penggajian';
    protected static string $view             = 'filament.pages.histori-center';
    protected static ?int $navigationSort     = 5;
    
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
