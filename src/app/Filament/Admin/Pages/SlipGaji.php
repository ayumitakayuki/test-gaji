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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select as FormSelect;
use Filament\Notifications\Notification;
use App\Services\SlipGajiBatchService;

class SlipGaji extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Slip Gaji';
    protected static ?string $title = 'Slip Gaji';
    protected static string $view = 'filament.pages.slip-gaji';

    public ?string $start_date = null;
    public ?string $end_date = null;
    public string $tipe_pembayaran = 'payroll';

    public function mount(): void
    {
        $today = Carbon::today();

        $this->start_date = $today->day <= 15
            ? $today->copy()->startOfMonth()->toDateString()
            : $today->copy()->day(16)->toDateString();

        $this->end_date = $today->toDateString();
        $this->tipe_pembayaran = 'payroll';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Penggajian';
    }

    // FIX 1: Filament v3 menggunakan method table(), bukan getTableQuery/getTableColumns/getTableFilters
    public function table(Table $table): Table
    {
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

                Tables\Columns\TextColumn::make('aksi')
                    ->label('Aksi')
                    ->html()
                    ->getStateUsing(fn ($record) =>
                        '<a href="' . route('filament.admin.pages.slip-gaji-hitung', [
                            'karyawan_id'      => $record->id_karyawan,
                            'start_date'       => $this->start_date,
                            'end_date'         => $this->end_date,
                            'tipe_pembayaran'  => $this->tipe_pembayaran,
                        ]) . '" class="text-blue-600 hover:underline">Buat Slip</a>'
                    )
                    ->alignCenter(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('atur_periode')
                    ->label('Atur Periode')
                    ->icon('heroicon-o-calendar-days')
                    ->form([
                        DatePicker::make('start_date')
                            ->label('Periode Awal')
                            ->required()
                            ->default($this->start_date),

                        DatePicker::make('end_date')
                            ->label('Periode Akhir')
                            ->required()
                            ->default($this->end_date),

                        FormSelect::make('tipe_pembayaran')
                            ->label('Tipe Pembayaran')
                            ->options([
                                'payroll' => 'Payroll',
                                'non_payroll' => 'Non Payroll',
                            ])
                            ->default($this->tipe_pembayaran)
                            ->required(),
                    ])
                    ->fillForm(fn () => [
                        'start_date' => $this->start_date,
                        'end_date' => $this->end_date,
                        'tipe_pembayaran' => $this->tipe_pembayaran,
                    ])
                    ->action(function (array $data) {
                        $this->start_date = $data['start_date'];
                        $this->end_date = $data['end_date'];
                        $this->tipe_pembayaran = $data['tipe_pembayaran'];

                        $this->resetTable();

                        Notification::make()
                            ->title('Periode berhasil diterapkan')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('generate_semua')
                    ->label('Generate Semua Slip')
                    ->icon('heroicon-o-bolt')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Generate semua slip gaji?')
                    ->modalDescription('Sistem akan membuat slip gaji semua karyawan yang punya rekap pada periode aktif.')
                    ->action(function () {
                        $result = app(SlipGajiBatchService::class)
                            ->generateManyByPeriod(
                                startDate: $this->start_date,
                                endDate: $this->end_date,
                                tipePembayaran: $this->tipe_pembayaran,
                            );

                        Notification::make()
                            ->title('Generate slip selesai')
                            ->body("Berhasil: {$result['success']} dari {$result['total']} karyawan. Gagal: {$result['failed']}.")
                            ->success()
                            ->send();

                        $this->resetTable();
                    }),
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