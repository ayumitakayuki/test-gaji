<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\TableWidget;
use Filament\Tables;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PersonalActivityWidget extends TableWidget
{
    // Urutan tampil; boleh diubah
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    /** Tentukan siapa yang boleh melihat widget ini */
    public static function canView(): bool
    {
        return Gate::allows('penggajian.process') // untuk staf admin
            || Gate::allows('absensi.validate')
            || Gate::allows('karyawan.manage')
            || Gate::allows('kasbon.process');    // untuk staf kasbon
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