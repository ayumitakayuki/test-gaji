<?php

namespace App\Filament\Admin\Widgets;

use App\Models\KasbonLoan;
use App\Models\KasbonPayment;
use App\Models\KasbonRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;

class KasbonDashboardStats extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        return $user && (
            $user->can('view_any_kasbon::loan')
            || $user->can('view_any_kasbon::payment')
        );
    }

    protected function getStats(): array
    {
        $now = now()->locale('id');

        return [
            Stat::make('Selamat Datang', Auth::user()?->name ?? 'User')
                ->description($now->translatedFormat('l, d F Y') . ' · ' . $now->format('H:i')),

            Stat::make('Aktivitas Saya Hari Ini', Activity::query()
                ->where('causer_id', Auth::id())
                ->whereDate('created_at', today())
                ->count()),

            Stat::make('Pengajuan Baru', KasbonRequest::query()
                ->whereIn('status_awal', ['draft', 'waiting_staff_verif'])
                ->count()),

            Stat::make('Loan Aktif', KasbonLoan::query()
                ->where('status', 'aktif')
                ->count()),

            Stat::make('Pembayaran Bulan Ini', 'Rp ' . number_format(
                KasbonPayment::query()
                    ->whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year)
                    ->sum('nominal'),
                0,
                ',',
                '.'
            )),
        ];
    }
}