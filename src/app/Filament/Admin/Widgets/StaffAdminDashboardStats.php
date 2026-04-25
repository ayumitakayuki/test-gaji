<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Absensi;
use App\Models\Gaji;
use App\Models\Karyawan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\Activitylog\Models\Activity;

class StaffAdminDashboardStats extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return Gate::allows('penggajian.process')
            || Gate::allows('absensi.validate')
            || Gate::allows('karyawan.manage');
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

            Stat::make('Total Karyawan', Karyawan::count()),

            Stat::make('Absensi Pending', Absensi::query()
                ->where('is_approved', false)
                ->where('is_declined', false)
                ->count()),

            Stat::make('Slip Gaji Bulan Ini', Gaji::query()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count()),
        ];
    }
}