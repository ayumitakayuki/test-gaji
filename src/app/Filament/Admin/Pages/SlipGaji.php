<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Karyawan;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\SelectFilter;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SlipGaji extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Slip Gaji';
    protected static ?string $title = 'Slip Gaji';
    protected static string $view = 'filament.pages.slip-gaji';

    public static function getNavigationGroup(): ?string
    {
        return 'Penggajian';
    }

    // FIX 1: Filament v3 menggunakan method table(), bukan getTableQuery/getTableColumns/getTableFilters
    public function table(Table $table): Table
    {
        // FIX 2: default tanggal — periode setengah bulan berjalan saat ini
        $today = Carbon::today();
        $defaultStart = $today->day <= 15
            ? $today->copy()->startOfMonth()->toDateString()          // 1 s/d hari ini (periode 1–15)
            : $today->copy()->day(16)->toDateString();                 // 16 s/d hari ini (periode 16–akhir)
        $defaultEnd = $today->toDateString();

        return $table
            ->query(
                Karyawan::query()
                    ->whereHas('rekaps')
                    ->orderBy('id', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('id_karyawan')
                    ->label('ID Karyawan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('lokasi')
                    ->label('Lokasi'),

                Tables\Columns\TextColumn::make('jenis_proyek')
                    ->label('Proyek'),

                // FIX 3: link "Buat Slip" sekarang membawa start_date dan end_date
                Tables\Columns\TextColumn::make('aksi')
                    ->label('Aksi')
                    ->html()
                    ->getStateUsing(fn ($record) =>
                        '<a href="' . route('filament.admin.pages.slip-gaji-hitung', [
                            'karyawan_id' => $record->id_karyawan,
                            'start_date'  => $defaultStart,
                            'end_date'    => $defaultEnd,
                        ]) . '" class="text-blue-600 hover:underline">Buat Slip</a>'
                    )
                    ->alignCenter(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'harian lepas' => 'Harian Lepas',
                        'kontrak'      => 'Kontrak',
                        'tetap'        => 'Tetap',
                    ])
                    ->searchable(),

                SelectFilter::make('lokasi')
                    ->label('Lokasi')
                    ->options(
                        Karyawan::query()
                            ->whereNotNull('lokasi')
                            ->distinct()
                            ->pluck('lokasi', 'lokasi')
                            ->toArray()
                    )
                    ->searchable(),

                SelectFilter::make('jenis_proyek')
                    ->label('Proyek')
                    ->options(
                        Karyawan::query()
                            ->whereNotNull('jenis_proyek')
                            ->distinct()
                            ->pluck('jenis_proyek', 'jenis_proyek')
                            ->toArray()
                    )
                    ->searchable(),
            ]);
    }

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user && (
            $user->can('page_SlipGaji')
            || $user->can('page_HistoriSlipGaji')
        );
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }
}