<?php

namespace App\Filament\Admin\Pages;

use App\Models\RekapGajiPeriod;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RekapGajiVerifikasiDO extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon  = 'heroicon-o-check-circle';
    protected static ?string $title           = 'Verifikasi Rekap Gaji';
    protected static ?string $navigationGroup = 'Direktur Operasional';
    protected static string $view             = 'filament.pages.rekap-gaji-verifikasi-d-o';

    public static function canAccess(): bool
    {
        return Gate::allows('penggajian.approve');
    }

    protected function getTableQuery(): Builder
    {
        return RekapGajiPeriod::query()
            ->where('status_do', 'waiting_do')
            ->latest('start_date');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('start_date')
                ->label('Periode')
                ->formatStateUsing(fn ($state, $record) =>
                    ($record->start_date?->format('d M Y') ?? '-') . ' - ' .
                    ($record->end_date?->format('d M Y') ?? '-')
                ),

            Tables\Columns\TextColumn::make('total_payroll')
                ->label('Total Payroll')
                ->alignRight()
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),

            Tables\Columns\TextColumn::make('count_payroll')
                ->label('Karyawan Payroll')
                ->alignCenter(),

            Tables\Columns\TextColumn::make('total_non_payroll')
                ->label('Total Non Payroll')
                ->alignRight()
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),

            Tables\Columns\TextColumn::make('count_non_payroll')
                ->label('Karyawan Non Payroll')
                ->alignCenter(),

            Tables\Columns\TextColumn::make('total_grand')
                ->label('Grand Total')
                ->alignRight()
                ->weight('bold')
                ->color('success')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),

            Tables\Columns\TextColumn::make('count_grand')
                ->label('Total Karyawan')
                ->alignCenter(),

            Tables\Columns\TextColumn::make('status_do')
                ->label('Status DO')
                ->badge()
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'draft'       => 'Draft',
                    'waiting_do'  => 'Menunggu DO',
                    'approved_do' => 'Approved DO',
                    'rejected_do' => 'Rejected DO',
                    default       => $state,
                })
                ->color(fn (string $state): string => match ($state) {
                    'draft'       => 'secondary',
                    'waiting_do'  => 'warning',
                    'approved_do' => 'success',
                    'rejected_do' => 'danger',
                    default       => 'secondary',
                }),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('lihat_pdf')
                ->label('Lihat PDF')
                ->icon('heroicon-o-document')
                ->url(fn (RekapGajiPeriod $record) => route('rekap-gaji-periode-export', [
                    'rekap_ids' => [$record->id],
                ]))
                ->openUrlInNewTab(),

            Action::make('approve')
                ->label('Approve')
                ->color('success')
                ->requiresConfirmation()
                ->action(function (RekapGajiPeriod $record) {
                    $record->update([
                        'status_do'      => 'approved_do',
                        'approved_do_by' => Auth::id(),
                        'approved_do_at' => now(),
                    ]);

                    Notification::make()
                        ->title('Rekap disetujui')
                        ->success()
                        ->send();
                }),

            Action::make('reject')
                ->label('Reject')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function (RekapGajiPeriod $record) {
                    $record->update([
                        'status_do'      => 'rejected_do',
                        'approved_do_by' => Auth::id(),
                        'approved_do_at' => now(),
                    ]);

                    Notification::make()
                        ->title('Rekap ditolak')
                        ->danger()
                        ->send();
                }),
        ];
    }
}