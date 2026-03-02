<?php

namespace App\Filament\Admin\Pages;

use App\Models\KasbonRequest;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class KasbonVerifikasiAwalDO extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon  = 'heroicon-o-check-circle';
    protected static ?string $navigationLabel = 'Verifikasi Awal Kasbon';
    protected static ?string $title           = 'Kasbon Verification (Awal)';
    protected static ?string $navigationGroup = 'Direktur Operasional';
    protected static ?int $navigationSort     = 1;

    protected static string $view = 'filament.pages.kasbon-verifikasi-awal-do';

    public static function canAccess(): bool
    {
        return Gate::allows('kasbon.approve');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    protected function getTableQuery(): Builder
    {
        return KasbonRequest::query()
            ->with('karyawan')
            ->where('status_awal', 'waiting_do_awal')
            ->orderByDesc('tanggal_pengajuan');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('karyawan.nama')->label('Nama Karyawan')->searchable(),
                Tables\Columns\TextColumn::make('tanggal_pengajuan')->label('Tanggal')->date('d-m-Y'),
                Tables\Columns\TextColumn::make('nominal')->label('Nominal')->money('IDR', true),
                Tables\Columns\TextColumn::make('tenor')->label('X'),
                Tables\Columns\TextColumn::make('cicilan')->label('Cicilan')->money('IDR', true),

                Tables\Columns\BadgeColumn::make('status_awal')
                    ->label('Status Awal')
                    ->formatStateUsing(fn () => 'Waiting')
                    ->color('warning'),
            ])
            ->actions([
                Action::make('detail')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => route('filament.admin.pages.kasbon-detail-d-o', ['id' => $record->id, 'tab' => 'awal'])),

                Action::make('approve_awal')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status_awal' => 'approved_do_awal',
                            'status_akhir' => 'waiting_staff_akhir',
                            'approved_awal_by' => Auth::id(),
                            'approved_awal_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Approved Tahap Awal')
                            ->success()
                            ->send();
                    }),

                Action::make('reject_awal')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status_awal' => 'rejected_do_awal',
                            'approved_awal_by' => Auth::id(),
                            'approved_awal_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Rejected Tahap Awal')
                            ->danger()
                            ->send();
                    }),
            ]);
    }
}
