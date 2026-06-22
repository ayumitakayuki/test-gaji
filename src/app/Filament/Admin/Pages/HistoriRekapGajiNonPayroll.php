<?php

namespace App\Filament\Admin\Pages;

use App\Models\RekapGajiNonPayroll;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms;
use App\Models\RekapGajiNonPayroll as RekapNonPayrollHeader;
use App\Filament\Admin\Pages\RekapGajiNonPayroll as RekapNonPayrollPage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use App\Models\User;

class HistoriRekapGajiNonPayroll extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon  = 'heroicon-o-folder-minus';
    protected static ?string $title           = 'Histori Rekap Gaji Non Payroll';
    protected static ?string $navigationLabel = 'Histori Rekap Non Payroll';
    protected static ?string $navigationGroup = 'Laporan Gaji';
    protected static ?int    $navigationSort  = 6;
    protected static string $view             = 'filament.pages.histori-rekap-gaji-non-payroll';

    public static function getSlug(): string
    {
        return 'histori-rekap-gaji-non-payroll';
    }
    protected function getTableQuery(): Builder
    {
        return RekapGajiNonPayroll::query()->latest('period_start');
    }

    protected function getTableColumns(): array
    {
        $idr = fn ($v) => 'Rp ' . number_format((float) $v, 0, ',', '.');

        return [
            Tables\Columns\TextColumn::make('range_type')
                ->label('Range')
                ->badge()
                ->formatStateUsing(fn ($state) => match ($state) {
                    'first'  => '01–15',
                    'second' => '16–Akhir',
                    default  => 'Custom',
                })
                ->color(fn ($state) => match ($state) {
                    'first'  => 'info',
                    'second' => 'success',
                    default  => 'gray',
                })
                ->sortable(),

            Tables\Columns\TextColumn::make('period_start')
                ->label('Periode')
                ->getStateUsing(fn ($record) =>
                    Carbon::parse($record->period_start)->format('d M Y') . ' - ' .
                    Carbon::parse($record->period_end)->format('d M Y')
                )
                ->sortable(),

            Tables\Columns\TextColumn::make('rows_count')
                ->label('Baris')
                ->alignCenter()
                ->sortable(),

            Tables\Columns\TextColumn::make('total_pembulatan')
                ->label('Pembulatan (Grand)')
                ->alignRight()
                ->formatStateUsing(fn ($state) => $idr($state))
                ->sortable(),

            Tables\Columns\TextColumn::make('total_kasbon')
                ->label('Kasbon')
                ->alignRight()
                ->formatStateUsing(fn ($state) => $idr($state))
                ->sortable(),

            Tables\Columns\TextColumn::make('total_sisa_kasbon')
                ->label('Sisa Kasbon')
                ->alignRight()
                ->formatStateUsing(fn ($state) => $idr($state))
                ->sortable(),

            Tables\Columns\TextColumn::make('total_total_setelah_bon')
                ->label('Total Setelah Bon')
                ->alignRight()
                ->formatStateUsing(fn ($state) => $idr($state))
                ->sortable(),
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
                ->url(fn ($record) => RekapNonPayrollPage::getUrl([
                    'start_date' => Carbon::parse($record->period_start)->format('Y-m-d'),
                    'end_date'   => Carbon::parse($record->period_end)->format('Y-m-d'),
                ]))
                ->openUrlInNewTab(),

            // Tables\Actions\Action::make('recalc')
            //     ->label('Recalculate Totals')
            //     ->icon('heroicon-o-arrow-path')
            //     ->color('gray')
            //     ->action(function (RekapNonPayrollHeader $record) {
            //         $agg = $record->rows()->selectRaw("
            //             COUNT(*)                               as rows_count,
            //             COALESCE(SUM(pembulatan),0)            as total_pembulatan,
            //             COALESCE(SUM(kasbon),0)                as total_kasbon,
            //             COALESCE(SUM(sisa_kasbon),0)           as total_sisa_kasbon,
            //             COALESCE(SUM(total_setelah_bon),0)     as total_total_setelah_bon
            //         ")->first();

            //         $record->update($agg->toArray());
            //     }),

            Tables\Actions\DeleteAction::make(),
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            Tables\Actions\DeleteBulkAction::make(),
        ];
    }

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user && $user->can('page_HistoriRekapGajiNonPayroll');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

}
