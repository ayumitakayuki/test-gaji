<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use App\Models\Gaji;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
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
        /** @var User|null $user */
        $user = Auth::user();

        return request()->has('id')
            && $user
            && $user->can('page_DetailSlipGaji');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }
    }

