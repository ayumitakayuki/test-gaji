<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\TableWidget;
use Filament\Tables;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class PersonalActivityWidget extends TableWidget
{
    // Urutan tampil; boleh diubah
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    /** Tentukan siapa yang boleh melihat widget ini */
    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        return $user && (
            $user->can('view_any_absensi')
            || $user->can('view_any_karyawan')
            || $user->can('page_SlipGaji')
            || $user->can('view_any_kasbon::loan')
            || $user->can('view_any_kasbon::payment')
        );
    }

    /** Query data aktivitas: hanya log milik user login */
    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation|null
    {
        return Activity::query()
            ->where('causer_id', Auth::id())
            ->latest()
            ->limit(5);
    }

    /** Definisikan kolom yang ingin ditampilkan */
    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('description')
                ->label('Keterangan')
                ->wrap(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Waktu')
                ->datetime('d/m/Y H:i'),
        ];
    }
}