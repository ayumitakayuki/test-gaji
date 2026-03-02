<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Gate;

class KasbonCenter extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Kasbon';
    protected static ?string $title           = 'Kasbon';
    protected static ?string $navigationGroup = 'Penggajian';
    protected static string $view             = 'filament.pages.kasbon-center';
    protected static ?int $navigationSort     = 4;

    public static function canAccess(): bool
    {
        return Gate::allows('kasbon.process');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }
}
