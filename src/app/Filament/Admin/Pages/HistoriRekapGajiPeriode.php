<?php

namespace App\Filament\Admin\Pages;

use App\Models\RekapGajiPeriod;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\Filter;
use Filament\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\DatePicker;
use App\Models\User;

class HistoriRekapGajiPeriode extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-folder-plus';
    protected static string $view = 'filament.pages.histori-rekap-gaji-periode';
    protected static ?string $title = 'Histori Rekap Gaji';
        protected static ?string $navigationGroup = 'Laporan Gaji';
    protected static ?int    $navigationSort  = 4;

    public static function getNavigationGroup(): ?string
    {
        return 'Laporan Gaji';
    }

    protected function getTableQuery(): Builder
    {
        return RekapGajiPeriod::query()
            ->withCount('rows')
            ->withSum([
                'rows as calc_total_payroll' => fn ($q) =>
                    $q->where('keterangan', 'TOTAL PAYROLL'),
            ], 'jumlah')
            ->withSum([
                'rows as calc_total_non_payroll' => fn ($q) =>
                    $q->whereIn('keterangan', ['TOTAL CASH', 'TOTAL NON PAYROLL', 'Gaji Harian']),
            ], 'jumlah')
            ->withSum([
                'rows as calc_total_grand' => fn ($q) =>
                    $q->where('keterangan', 'Grand Total'),
            ], 'jumlah')
            ->latest('start_date');
    }
    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('start_date')
                ->label('Periode')
                ->sortable()
                ->formatStateUsing(fn ($state, $record) =>
                    ($record->start_date?->format('d M Y') ?? '-') . ' - ' .
                    ($record->end_date?->format('d M Y') ?? '-')
                ),
            Tables\Columns\TextColumn::make('rows_count')
                ->label('Baris')
                ->badge(),

            Tables\Columns\TextColumn::make('calc_total_payroll')
                ->label('Total Payroll')
                ->alignRight()
                ->getStateUsing(function ($record) {
                    $total = (int) round((float) ($record->calc_total_payroll ?? $record->total_payroll ?? 0));

                    return 'Rp ' . number_format($total, 0, ',', '.');
                }),

            Tables\Columns\TextColumn::make('calc_total_non_payroll')
                ->label('Total Non Payroll')
                ->alignRight()
                ->getStateUsing(function ($record) {
                    $total = (int) round((float) ($record->calc_total_non_payroll ?? $record->total_non_payroll ?? 0));

                    return 'Rp ' . number_format($total, 0, ',', '.');
                }),

            Tables\Columns\TextColumn::make('calc_total_grand')
                ->label('Grand Total')
                ->alignRight()
                ->color('success')
                ->weight('bold')
                ->getStateUsing(function ($record) {
                    $total = (int) round((float) ($record->calc_total_grand ?? $record->total_grand ?? 0));

                    return 'Rp ' . number_format($total, 0, ',', '.');
                }),

            Tables\Columns\TextColumn::make('created_by')
                ->label('Dibuat oleh')
                ->getStateUsing(fn ($record) => optional($record->user)->name ?? '-'),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime('d M Y H:i'),


            BadgeColumn::make('status_do')
                ->label('Status DO')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'draft'      => 'Draft',
                    'waiting_do' => 'Menunggu DO',
                    'approved_do'=> 'Disetujui DO',
                    'rejected_do'=> 'Ditolak DO',
                    default      => $state,
                })
                ->colors([
                    'secondary' => 'draft',
                    'warning'   => 'waiting_do',
                    'success'   => 'approved_do',
                    'danger'    => 'rejected_do',
                ]),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Filter::make('periode')
                ->form([
                    DatePicker::make('tanggal_awal')
                        ->label('Tanggal Awal'),
                    DatePicker::make('tanggal_akhir')
                        ->label('Tanggal Akhir'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['tanggal_awal'],
                            fn (Builder $query, $date): Builder => $query->whereDate('periode_awal', '>=', $date),
                        )
                        ->when(
                            $data['tanggal_akhir'],
                            fn (Builder $query, $date): Builder => $query->whereDate('periode_akhir', '<=', $date),
                        );
                })
                ->label('Periode'),
        ];
    }
    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('open')
                ->label('Buka Rekap')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn (RekapGajiPeriod $record) =>
                    \App\Filament\Admin\Pages\RekapGajiPeriode::getUrl(['rekap_id' => $record->id])
                )
                ->openUrlInNewTab(),

            Tables\Actions\Action::make('kirim_do')
                ->label('Kirim ke DO')
                ->icon('heroicon-o-paper-airplane')
                ->visible(fn (RekapGajiPeriod $record) =>
                    $record->status_do === 'draft' // hanya tampil untuk rekap yang belum dikirim
                )
                ->requiresConfirmation()
                ->color('warning')
                ->action(function (RekapGajiPeriod $record) {
                    $record->update([
                        'status_do'      => 'waiting_do',
                        'approved_do_by' => null,
                        'approved_do_at' => null,
                    ]);
                    Notification::make()->title('Rekap dikirim ke DO')->success()->send();
                }),
        ];
    }
    protected function getTableBulkActions(): array
    {
        return [
            Tables\Actions\DeleteBulkAction::make()
                ->label('Hapus Terpilih')
                ->requiresConfirmation(),
        ];
    }

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user && $user->can('page_HistoriRekapGajiPeriode');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

}
