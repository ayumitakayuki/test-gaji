<?php

namespace App\Filament\Admin\Pages;

use App\Models\KasbonRequest;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class KasbonPinjamanStaffKasbon extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon  = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Kasbon Pinjaman';
    protected static ?string $title           = 'Kasbon Pinjaman';
    protected static ?string $navigationGroup = 'Kasbon';
    protected static ?int $navigationSort     = 1;

    protected static string $view = 'filament.pages.kasbon-pinjaman-staff-kasbon';

    public string $tab = 'awal';

    // ✅ HRBAC UI
    public static function canAccess(): bool
    {
        return Gate::allows('kasbon.process');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function mount(): void
    {
        $this->tab = request('tab', 'awal');
    }

    protected function getTableQuery(): Builder
    {
        $query = KasbonRequest::query()->with('karyawan');

        // ✅ tab filter
        if ($this->tab === 'akhir') {
            // verifikasi akhir (urgensi)
            $query->where('status_awal', 'approved_do_awal')
                ->where('status_akhir', 'waiting_staff_akhir');
        } else {
            // verifikasi awal
            $query->whereIn('status_awal', ['draft', 'waiting_do_awal']);
        }

        return $query;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('karyawan.nama')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_pengajuan')
                    ->label('Tanggal')
                    ->date('d-m-Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('nominal')
                    ->label('Nominal')
                    ->money('IDR', true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('tenor')
                    ->label('X')
                    ->sortable(),

                Tables\Columns\TextColumn::make('cicilan')
                    ->label('Cicilan')
                    ->money('IDR', true)
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('prioritas')
                    ->label('Prioritas')
                    ->formatStateUsing(fn($state) => $state ? ucfirst($state) : '-')
                    ->colors([
                        'success' => 'rendah',
                        'warning' => 'sedang',
                        'danger'  => 'tinggi',
                    ]),

                Tables\Columns\BadgeColumn::make('status_awal')
                    ->label('Status Awal')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'draft' => 'Waiting',
                        'waiting_do_awal' => 'Menunggu DO',
                        'approved_do_awal' => 'Pass Tahap 1',
                        'rejected_do_awal' => 'Rejected',
                        default => $state,
                    })
                    ->colors([
                        'warning' => ['draft', 'waiting_do_awal'],
                        'success' => ['approved_do_awal'],
                        'danger' => ['rejected_do_awal'],
                    ]),
            ])
            ->headerActions([
                Action::make('new_data')
                    ->label('NEW DATA')
                    ->icon('heroicon-o-plus')
                    ->color('primary')
                    ->visible(fn () => $this->tab === 'awal')
                    ->url(fn () => route('filament.admin.pages.kasbon-pengajuan-staff-kasbon')),
            ])
            ->actions($this->tab === 'akhir'
                ? $this->getActionsAkhir()
                : $this->getActionsAwal()
            )
            ->defaultSort('tanggal_pengajuan', 'desc');
    }

    // ✅ Actions tab awal
    protected function getActionsAwal(): array
    {
        return [
            Action::make('detail')
                ->label('Detail')
                ->icon('heroicon-o-eye')
                ->url(fn ($record) => route('filament.admin.pages.kasbon-pengajuan-staff-kasbon', ['id' => $record->id])),

            Action::make('kirim_do')
                ->label('Kirim ke DO')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn ($record) => in_array($record->status_awal, ['draft','waiting_staff_verif']))
                ->action(function ($record) {
                    $record->update([
                        'status_awal' => 'waiting_do_awal',
                        'verified_by' => Auth::id(),
                        'verified_at' => now(),
                    ]);

                    Notification::make()
                        ->title('Berhasil dikirim ke DO (Tahap Awal)')
                        ->success()
                        ->send();
                }),

            Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn ($record) => in_array($record->status_awal, ['draft','waiting_staff_verif']))
                ->action(function ($record) {
                    $record->update([
                        'status_awal' => 'rejected_do_awal',
                    ]);

                    Notification::make()
                        ->title('Pengajuan ditolak')
                        ->danger()
                        ->send();
                }),
        ];
    }

    // ✅ Actions tab akhir
    protected function getActionsAkhir(): array
    {
        return [
            Action::make('detail')
                ->label('Detail')
                ->icon('heroicon-o-eye')
                ->url(fn ($record) => route('filament.admin.pages.kasbon-pengajuan-staff-kasbon', ['id' => $record->id])),

            Action::make('isi_urgensi')
                ->label('Isi Urgensi')
                ->icon('heroicon-o-pencil-square')
                ->color('warning')
                ->form([
                    Forms\Components\Select::make('prioritas')
                        ->label('Prioritas')
                        ->options([
                            'rendah' => 'Rendah',
                            'sedang' => 'Sedang',
                            'tinggi' => 'Tinggi',
                        ])
                        ->required(),

                    Forms\Components\Textarea::make('catatan_staff')
                        ->label('Catatan Staff')
                        ->rows(3)
                        ->required(),
                ])
                ->action(function ($record, array $data) {
                    $record->update([
                        'prioritas' => $data['prioritas'],
                        'catatan_staff' => $data['catatan_staff'],
                        'status_akhir' => 'waiting_do_akhir',
                    ]);

                    Notification::make()
                        ->title('Urgensi dikirim ke DO Final')
                        ->success()
                        ->send();
                }),
        ];
    }
}
