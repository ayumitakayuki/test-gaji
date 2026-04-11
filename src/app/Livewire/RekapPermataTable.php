<?php

// app/Livewire/RekapPermataTable.php
namespace App\Livewire;

use Livewire\Component;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\RekapTransferPermata;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

class RekapPermataTable extends Component implements HasTable
{
    use InteractsWithTable;

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(
                RekapTransferPermata::query()
                    ->where('status_do', 'waiting_do')
                    ->latest('period_start')
                    ->withCount('rows')
                    ->withSum('rows', 'transfer')
            )
            ->queryStringIdentifier('permata') // agar pagination tidak bentrok dengan tabel lainnya
            ->columns([
                Tables\Columns\TextColumn::make('period_start')
                    ->label('Periode')
                    ->getStateUsing(
                        fn ($record) => \Carbon\Carbon::parse($record->period_start)->format('d M Y') .
                            ' – ' .
                            \Carbon\Carbon::parse($record->period_end)->format('d M Y')
                    ),
                Tables\Columns\TextColumn::make('rows_count')->label('Baris')->alignCenter(),
                Tables\Columns\TextColumn::make('rows_sum_transfer')
                    ->label('Total Transfer')
                    ->alignRight()
                    ->formatStateUsing(
                        fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')
                    ),
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

    public function makeFilamentTranslatableContentDriver(): ?\Filament\Support\Contracts\TranslatableContentDriver
    {
        return null;
    }

    public function render()
    {
        // Gunakan komponen Blade bawaan Filament untuk merender tabel
        return view('livewire.rekap-permata-table');
    }
}