<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use App\Models\Gaji;
use Illuminate\Support\Facades\Gate;
class DetailSlipGaji extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-eye';
    protected static string $view = 'filament.pages.detail-slip-gaji';

    public ?Gaji $gaji = null;

    public function mount(): void
    {
        $id = request('id');
        $this->gaji = Gaji::with('details')->findOrFail($id);
    }

    public static function canAccess(): bool
    {
        return (request()->has('id')) && (
            Gate::allows('penggajian.process')
            || Gate::allows('absensi.validate')
            || Gate::allows('karyawan.manage')
        );
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }
    }

