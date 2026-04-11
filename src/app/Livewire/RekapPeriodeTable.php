<?php
// app/Livewire/RekapPeriodeTable.php
namespace App\Livewire;

use Livewire\Component;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\RekapGajiPeriod;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

class RekapPeriodeTable extends Component implements HasTable
{
    use InteractsWithTable;

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(
                RekapGajiPeriod::query()
                    ->where('status_do', 'waiting_do')
                    ->orderByDesc('start_date')
            )
            ->queryStringIdentifier('periode') // identitas query string untuk tabel ini
            ->columns([
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Periode')
                    ->getStateUsing(function ($record) {
                        return \Carbon\Carbon::parse($record->start_date)->format('d M Y') .
                            ' – ' .
                            \Carbon\Carbon::parse($record->end_date)->format('d M Y');
                    }),
                Tables\Columns\TextColumn::make('total_payroll')
                    ->label('Total Payroll')
                    ->alignRight()
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
                Tables\Columns\TextColumn::make('count_payroll')->label('Karyawan Payroll')->alignCenter(),
                Tables\Columns\TextColumn::make('total_non_payroll')
                    ->label('Total Non Payroll')
                    ->alignRight()
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
                Tables\Columns\TextColumn::make('count_non_payroll')->label('Karyawan Non Payroll')->alignCenter(),
                Tables\Columns\TextColumn::make('total_grand')
                    ->label('Grand Total')
                    ->alignRight()
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
                Tables\Columns\TextColumn::make('count_grand')->label('Total Karyawan')->alignCenter(),
            ])
            ->actions([
                Action::make('lihat_pdf')
                    ->label('Lihat PDF')
                    ->icon('heroicon-o-document')
                    ->url(fn ($record) => route('rekap-gaji-periode-export', [
                        'rekap_ids' => [$record->id],
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
        return view('livewire.rekap-periode-table');
    }
}