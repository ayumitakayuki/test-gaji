<?php

namespace App\Filament\Admin\Pages;

use App\Models\RekapTransferPermata;
use App\Models\RekapGajiPeriod;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class RekapGajiVerifikasiDO extends Page implements Tables\Contracts\HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon  = 'heroicon-o-check-circle';
    protected static ?string $title           = 'Verifikasi Rekap Gaji';
    protected static ?string $navigationGroup = 'Direktur Operasional';

    // Pastikan nama view sesuai dengan file Blade yang akan Anda buat
    protected static string $view = 'filament.pages.rekap-gaji-verifikasi-d-o';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->records(function (): Collection {
                $rows = collect();

                // Gabungkan rekap permata
                RekapTransferPermata::where('status_do', 'waiting_do')
                    ->each(function ($r) use (&$rows) {
                        $rows->push([
                            'tipe'  => 'Transfer Permata',
                            'model'=> 'permata',
                            'id'   => $r->id,
                            'start'=> $r->period_start,
                            'end'  => $r->period_end,
                            'total'=> $r->total_transfer,
                            'rows' => $r->rows_count,
                        ]);
                    });

                // Gabungkan rekap payroll, nonpayroll dan grand total
                RekapGajiPeriod::where('status_do', 'waiting_do')
                    ->each(function ($r) use (&$rows) {
                        $rows->push([
                            'tipe'  => 'Rekap Payroll',
                            'model'=> 'payroll',
                            'id'   => $r->id,
                            'start'=> $r->start_date,
                            'end'  => $r->end_date,
                            'total'=> $r->total_payroll,
                            'rows' => null,
                        ]);
                        $rows->push([
                            'tipe'  => 'Rekap Non Payroll',
                            'model'=> 'nonpayroll',
                            'id'   => $r->id,
                            'start'=> $r->start_date,
                            'end'  => $r->end_date,
                            'total'=> $r->total_non_payroll,
                            'rows' => null,
                        ]);
                        $rows->push([
                            'tipe'  => 'Rekap Grand Total',
                            'model'=> 'grand',
                            'id'   => $r->id,
                            'start'=> $r->start_date,
                            'end'  => $r->end_date,
                            'total'=> $r->total_grand,
                            'rows' => null,
                        ]);
                    });

                return $rows;
            })
            ->columns([
                Tables\Columns\TextColumn::make('tipe')->label('Tipe Rekap'),
                Tables\Columns\TextColumn::make('start')
                    ->label('Periode')
                    ->formatStateUsing(fn ($state, $record) =>
                        \Carbon\Carbon::parse($record['start'])->format('d M Y').' – '.\Carbon\Carbon::parse($record['end'])->format('d M Y')),
                Tables\Columns\TextColumn::make('rows')->label('Baris')->alignCenter()->getStateUsing(
                    fn ($record) => $record['rows'] ?? '-'
                ),
                Tables\Columns\TextColumn::make('total')->label('Total')->alignRight()->formatStateUsing(
                    fn ($state) => 'Rp '.number_format($state, 0, ',', '.')
                ),
            ])
            ->actions([
                Action::make('lihat_pdf')
                    ->label('Lihat PDF')
                    ->icon('heroicon-o-document')
                    ->url(fn (array $record) => match ($record['model']) {
                        'permata' => route('rekap-transfer-permata.pdf', [
                            'start_date' => $record['start'],
                            'end_date'   => $record['end'],
                        ]),
                        default   => route('rekap-gaji-periode-export', [
                            'rekap_ids' => [$record['id']],
                        ]),
                    })
                    ->openUrlInNewTab(),
                Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (array $record) {
                        if ($record['model'] === 'permata') {
                            RekapTransferPermata::find($record['id'])->update([
                                'status_do'      => 'approved_do',
                                'approved_do_by' => Auth::id(),
                                'approved_do_at' => now(),
                            ]);
                        } else {
                            RekapGajiPeriod::find($record['id'])->update([
                                'status_do'      => 'approved_do',
                                'approved_do_by' => Auth::id(),
                                'approved_do_at' => now(),
                            ]);
                        }
                        Notification::make()->title('Rekap disetujui')->success()->send();
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (array $record) {
                        if ($record['model'] === 'permata') {
                            RekapTransferPermata::find($record['id'])->update([
                                'status_do'      => 'rejected_do',
                                'approved_do_by' => Auth::id(),
                                'approved_do_at' => now(),
                            ]);
                        } else {
                            RekapGajiPeriod::find($record['id'])->update([
                                'status_do'      => 'rejected_do',
                                'approved_do_by' => Auth::id(),
                                'approved_do_at' => now(),
                            ]);
                        }
                        Notification::make()->title('Rekap ditolak')->danger()->send();
                    }),
            ]);
    }
}