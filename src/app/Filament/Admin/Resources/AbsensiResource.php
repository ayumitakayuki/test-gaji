<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AbsensiResource\Pages;
use App\Models\Absensi;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Arr;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;


class AbsensiResource extends Resource
{
    protected static ?string $model = Absensi::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'List Absensi';
    protected static ?string $navigationGroup = 'Absensi';
    protected static ?int    $navigationSort  = 2;
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required(),

                Forms\Components\DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->required(),

                Forms\Components\TimePicker::make('masuk_pagi')
                    ->label('Masuk Pagi')
                    ->seconds(true),

                Forms\Components\TimePicker::make('keluar_siang')
                    ->label('Keluar Siang')
                    ->seconds(true),

                Forms\Components\TimePicker::make('masuk_siang')
                    ->label('Masuk Siang')
                    ->seconds(true),

                Forms\Components\TimePicker::make('pulang_kerja')
                    ->label('Pulang Kerja')
                    ->seconds(true),

                Forms\Components\TimePicker::make('masuk_lembur')
                    ->label('Masuk Lembur')
                    ->seconds(true),

                Forms\Components\TimePicker::make('pulang_lembur')
                    ->label('Pulang Lembur')
                    ->seconds(true),
                Forms\Components\Section::make('Detail Mobile - Masuk Pagi')
                    ->schema([
                        Forms\Components\TextInput::make('lat_masuk_pagi')->label('Lat Masuk Pagi'),
                        Forms\Components\TextInput::make('lng_masuk_pagi')->label('Lng Masuk Pagi'),
                        Forms\Components\TextInput::make('accuracy_masuk_pagi')->label('Akurasi Masuk Pagi'),
                        Forms\Components\Textarea::make('address_masuk_pagi')->label('Alamat Masuk Pagi')->rows(2),
                        Forms\Components\FileUpload::make('photo_path_masuk_pagi')
                            ->label('Foto Masuk Pagi')
                            ->disk('public')
                            ->directory('absensi'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Detail Mobile - Keluar Siang')
                    ->schema([
                        Forms\Components\TextInput::make('lat_keluar_siang')->label('Lat Keluar Siang'),
                        Forms\Components\TextInput::make('lng_keluar_siang')->label('Lng Keluar Siang'),
                        Forms\Components\TextInput::make('accuracy_keluar_siang')->label('Akurasi Keluar Siang'),
                        Forms\Components\Textarea::make('address_keluar_siang')->label('Alamat Keluar Siang')->rows(2),
                        Forms\Components\FileUpload::make('photo_path_keluar_siang')
                            ->label('Foto Keluar Siang')
                            ->disk('public')
                            ->directory('absensi'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Detail Mobile - Masuk Siang')
                    ->schema([
                        Forms\Components\TextInput::make('lat_masuk_siang')->label('Lat Masuk Siang'),
                        Forms\Components\TextInput::make('lng_masuk_siang')->label('Lng Masuk Siang'),
                        Forms\Components\TextInput::make('accuracy_masuk_siang')->label('Akurasi Masuk Siang'),
                        Forms\Components\Textarea::make('address_masuk_siang')->label('Alamat Masuk Siang')->rows(2),
                        Forms\Components\FileUpload::make('photo_path_masuk_siang')
                            ->label('Foto Masuk Siang')
                            ->disk('public')
                            ->directory('absensi'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Detail Mobile - Pulang Kerja')
                    ->schema([
                        Forms\Components\TextInput::make('lat_pulang_kerja')->label('Lat Pulang Kerja'),
                        Forms\Components\TextInput::make('lng_pulang_kerja')->label('Lng Pulang Kerja'),
                        Forms\Components\TextInput::make('accuracy_pulang_kerja')->label('Akurasi Pulang Kerja'),
                        Forms\Components\Textarea::make('address_pulang_kerja')->label('Alamat Pulang Kerja')->rows(2),
                        Forms\Components\FileUpload::make('photo_path_pulang_kerja')
                            ->label('Foto Pulang Kerja')
                            ->disk('public')
                            ->directory('absensi'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Detail Mobile - Masuk Lembur')
                    ->schema([
                        Forms\Components\TextInput::make('lat_masuk_lembur')->label('Lat Masuk Lembur'),
                        Forms\Components\TextInput::make('lng_masuk_lembur')->label('Lng Masuk Lembur'),
                        Forms\Components\TextInput::make('accuracy_masuk_lembur')->label('Akurasi Masuk Lembur'),
                        Forms\Components\Textarea::make('address_masuk_lembur')->label('Alamat Masuk Lembur')->rows(2),
                        Forms\Components\FileUpload::make('photo_path_masuk_lembur')
                            ->label('Foto Masuk Lembur')
                            ->disk('public')
                            ->directory('absensi'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Detail Mobile - Pulang Lembur')
                    ->schema([
                        Forms\Components\TextInput::make('lat_pulang_lembur')->label('Lat Pulang Lembur'),
                        Forms\Components\TextInput::make('lng_pulang_lembur')->label('Lng Pulang Lembur'),
                        Forms\Components\TextInput::make('accuracy_pulang_lembur')->label('Akurasi Pulang Lembur'),
                        Forms\Components\Textarea::make('address_pulang_lembur')->label('Alamat Pulang Lembur')->rows(2),
                        Forms\Components\FileUpload::make('photo_path_pulang_lembur')
                            ->label('Foto Pulang Lembur')
                            ->disk('public')
                            ->directory('absensi'),
                    ])
                    ->columns(2),
                Forms\Components\Textarea::make('declined_reason')
                    ->label('Alasan Ditolak')
                    ->disabled()
                    ->visible(fn ($record) => $record?->is_declined),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),

                TextColumn::make('masuk_pagi')->label('Masuk Pagi'),
                TextColumn::make('keluar_siang')->label('Keluar Siang'),
                TextColumn::make('masuk_siang')->label('Masuk Siang'),
                TextColumn::make('pulang_kerja')->label('Pulang Kerja'),
                TextColumn::make('masuk_lembur')->label('Masuk Lembur'),
                TextColumn::make('pulang_lembur')->label('Pulang Lembur'),
                
                TextColumn::make('keterangan_mobile')
                    ->label('Keterangan Mobile')
                    ->getStateUsing(function (Absensi $record) {
                        $items = [];

                        if ($record->masuk_pagi) $items[] = 'Masuk Pagi';
                        if ($record->keluar_siang) $items[] = 'Keluar Siang';
                        if ($record->masuk_siang) $items[] = 'Masuk Siang';
                        if ($record->pulang_kerja) $items[] = 'Pulang Kerja';
                        if ($record->masuk_lembur) $items[] = 'Masuk Lembur';
                        if ($record->pulang_lembur) $items[] = 'Pulang Lembur';

                        return empty($items) ? '-' : implode(', ', $items);
                    })
                    ->wrap(),
                // IconColumn::make('has_mobile_photo')
                //     ->label('Foto Mobile')
                //     ->getStateUsing(fn (Absensi $record) =>
                //         filled($record->photo_path_masuk_pagi) ||
                //         filled($record->photo_path_keluar_siang) ||
                //         filled($record->photo_path_masuk_siang) ||
                //         filled($record->photo_path_pulang_kerja) ||
                //         filled($record->photo_path_masuk_lembur) ||
                //         filled($record->photo_path_pulang_lembur)
                //     )
                //     ->boolean(),
                TextColumn::make('status_absensi')
                    ->label('Status')
                    ->getStateUsing(function (Absensi $record) {
                        if ($record->is_declined) {
                            return 'Declined';
                        }

                        if ($record->is_approved) {
                            return 'Approved';
                        }

                        return 'Pending';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Approved' => 'success',
                        'Declined' => 'danger',
                        default => 'warning',
                    }),
            ])
        ->filters([
        // Periode tanggal (manual)
        Filter::make('periode_tanggal')
            ->form([
                Forms\Components\DatePicker::make('from')->label('Dari'),
                Forms\Components\DatePicker::make('until')->label('Sampai'),
            ])
            ->query(function ($query, array $data) {
                return $query
                    ->when($data['from']  ?? null, fn ($q, $date) => $q->whereDate('tanggal', '>=', $date))
                    ->when($data['until'] ?? null, fn ($q, $date) => $q->whereDate('tanggal', '<=', $date));
            }),

            // Status karyawan (opsi ditarik dari DB karyawan)
            SelectFilter::make('status')
                ->label('Status Karyawan')
                ->options(fn () => Karyawan::query()
                    ->whereNotNull('status')
                    ->distinct()
                    ->orderBy('status')
                    ->pluck('status', 'status')
                    ->toArray()
                )
                ->relationship('karyawan', 'status'),

            // Lokasi (opsi dari DB karyawan)
            SelectFilter::make('lokasi')
                ->label('Lokasi')
                ->options(fn () => Karyawan::query()
                    ->whereNotNull('lokasi')
                    ->distinct()
                    ->orderBy('lokasi')
                    ->pluck('lokasi', 'lokasi')
                    ->toArray()
                )
                ->relationship('karyawan', 'lokasi'),

            // Jenis Proyek (opsi dari DB karyawan.jenis_proyek), hanya hit untuk lokasi 'proyek'
            SelectFilter::make('jenis_proyek')
                ->label('Jenis Proyek')
                ->options(fn () => Karyawan::query()
                    ->where('lokasi', 'proyek')
                    ->whereNotNull('jenis_proyek')
                    ->distinct()
                    ->orderBy('jenis_proyek')
                    ->pluck('jenis_proyek', 'jenis_proyek')
                    ->toArray()
                )
                ->multiple()
                ->preload()
                ->query(function ($query, $state) {
                    // dukung struktur ['values'=>[...]] atau array langsung
                    $values = Arr::wrap(data_get($state, 'values', $state));
                    $values = array_values(array_filter(Arr::flatten($values), fn ($v) => filled($v)));
                    if (empty($values)) return $query;

                    return $query->whereHas('karyawan', function ($q) use ($values) {
                        $q->where('lokasi', 'proyek')
                        ->whereIn('jenis_proyek', $values);
                    });
                }),
            // SelectFilter::make('is_approved')
            //     ->label('Status Approval')
            //     ->options([
            //         '0' => 'Belum Disetujui',
            //         '1' => 'Sudah Disetujui',
            //     ])
            //     ->query(function ($query, $state) {
            //         // hanya terapkan filter jika state dipilih
            //         if (filled($state)) {
            //             $query->where('is_approved', $state);
            //         }
            //     }),
        ])
        ->paginationPageOptions([5, 10, 25, 50, 100, 'all'])
        ->actions([
            Tables\Actions\EditAction::make(),

            Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (Absensi $record) => !$record->is_approved && !$record->is_declined)
                ->action(function (Absensi $record) {
                    $record->update([
                        'is_approved'      => true,
                        'is_declined'      => false,
                        'declined_reason'  => null,
                        'declined_by'      => null,
                        'declined_at'      => null,
                        'approved_by'      => Auth::id(),
                        'approved_at'      => now(),
                    ]);
                }),

            Action::make('decline')
                ->label('Decline')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->form([
                    Forms\Components\Textarea::make('reason')
                        ->label('Alasan Penolakan')
                        ->required(),
                ])
                ->visible(fn (Absensi $record) => !$record->is_approved && !$record->is_declined)
                ->action(function (Absensi $record, array $data) {
                    $record->update([
                        'is_approved'      => false,
                        'is_declined'      => true,
                        'declined_reason'  => $data['reason'],
                        'declined_by'      => Auth::id(),
                        'declined_at'      => now(),
                    ]);
                }),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAbsensis::route('/'),
            'create' => Pages\CreateAbsensi::route('/create'),
            'edit' => Pages\EditAbsensi::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Gate::allows('absensi.validate');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }
    public static function canCreate(): bool
    {
        return Gate::allows('permission.nama');
    }

    public static function canEdit($record): bool
    {
        return Gate::allows('permission.nama');
    }

    public static function canDelete($record): bool
    {
        return Gate::allows('permission.nama');
    }

}
