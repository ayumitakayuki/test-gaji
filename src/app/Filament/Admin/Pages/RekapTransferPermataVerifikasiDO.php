<?php

namespace App\Filament\Admin\Pages;

use App\Models\RekapTransferPermata;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Support\Facades\Gate;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class RekapTransferPermataVerifikasiDO extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon  = 'heroicon-o-banknotes';
    protected static ?string $title           = 'Verifikasi Rekap Transfer Permata';
    protected static ?string $navigationGroup = 'Direktur Operasional';
    protected static string $view             = 'filament.pages.rekap-transfer-permata-verifikasi-do';

    public static function canAccess(): bool
    {
        return Gate::allows('penggajian.approve');
    }

    protected function getTableQuery()
    {
        // Hanya tampilkan rekap dengan status waiting_do
        return RekapTransferPermata::query()
            ->where('status_do', 'waiting_do')
            ->latest('period_start')
            ->withCount('rows')
            ->withSum('rows', 'transfer'); // 'transfer' adalah kolom total transfer
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('period_start')
                    ->label('Periode')
                    ->getStateUsing(fn ($record) => \Carbon\Carbon::parse($record->period_start)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($record->period_end)->format('d M Y')),
                Tables\Columns\TextColumn::make('rows_count')->label('Baris')->alignCenter(),
                Tables\Columns\TextColumn::make('rows_sum_transfer')
                    ->label('Total Transfer')
                    ->alignRight()
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
            ])
            ->actions([
                Action::make('lihat_pdf')
                    ->label('Lihat PDF')
                    ->icon('heroicon-o-document')
                    ->url(fn ($record) => route('rekap-transfer-permata.pdf', [
                        'start_date' => $record->period_start,
                        'end_date'   => $record->period_end,
                    ]))
                    ->openUrlInNewTab(),
                Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status_do'      => 'approved_do',
                            'approved_do_by' => Auth::id(),
                            'approved_do_at' => now(),
                        ]);
                        Notification::make()->title('Rekap disetujui')->success()->send();
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status_do'      => 'rejected_do',
                            'approved_do_by' => Auth::id(),
                            'approved_do_at' => now(),
                        ]);
                        Notification::make()->title('Rekap ditolak')->danger()->send();
                    }),
            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}