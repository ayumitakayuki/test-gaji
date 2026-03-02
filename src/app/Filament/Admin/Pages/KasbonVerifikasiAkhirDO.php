<?php

namespace App\Filament\Admin\Pages;

use App\Models\KasbonRequest;
use App\Models\KasbonLoan;
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

class KasbonVerifikasiAkhirDO extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon  = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'Verifikasi Akhir Kasbon';
    protected static ?string $title           = 'Kasbon Verification (Akhir)';
    protected static ?string $navigationGroup = 'Direktur Operasional';
    protected static ?int $navigationSort     = 2;

    protected static string $view = 'filament.pages.kasbon-verifikasi-akhir-do';

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
            ->where('status_akhir', 'waiting_do_akhir')
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
                Tables\Columns\BadgeColumn::make('prioritas')
                    ->label('Prioritas')
                    ->colors([
                        'success' => 'rendah',
                        'warning' => 'sedang',
                        'danger'  => 'tinggi',
                    ])
                    ->formatStateUsing(fn($state) => ucfirst($state)),

                Tables\Columns\TextColumn::make('catatan_staff')->label('Catatan Staff')->wrap(),

                Tables\Columns\BadgeColumn::make('status_akhir')
                    ->label('Status Akhir')
                    ->formatStateUsing(fn () => 'Waiting Final')
                    ->color('warning'),
            ])
            ->actions([
                Action::make('detail')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => route('filament.admin.pages.kasbon-detail-d-o', ['id' => $record->id, 'tab' => 'akhir'])),

                Action::make('approve_final')
                    ->label('Approve Final')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {

                        // ✅ generate KasbonLoan otomatis
                        $loan = KasbonLoan::create([
                            'karyawan_id' => $record->karyawan_id,
                            'tanggal'     => now(),
                            'pokok'       => $record->nominal,
                            'tenor'       => $record->tenor,
                            'cicilan'     => $record->cicilan,
                            'sisa_kali'   => $record->tenor,
                            'sisa_saldo'  => $record->nominal,
                            'status'      => 'aktif',
                            'keterangan'  => $record->alasan_pengajuan,
                        ]);

                        $record->update([
                            'status_akhir' => 'approved_do_akhir',
                            'approved_akhir_by' => Auth::id(),
                            'approved_akhir_at' => now(),
                            'kasbon_loan_id' => $loan->id,
                        ]);

                        Notification::make()
                            ->title('Approved Final — Kasbon Loan dibuat')
                            ->success()
                            ->send();
                    }),

                Action::make('reject_final')
                    ->label('Reject Final')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status_akhir' => 'rejected_do_akhir',
                            'approved_akhir_by' => Auth::id(),
                            'approved_akhir_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Rejected Tahap Akhir')
                            ->danger()
                            ->send();
                    }),
            ]);
    }
}
