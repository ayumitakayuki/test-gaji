<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KaryawanResource\Pages;
use App\Models\Karyawan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Filament\Forms\Get;
use Filament\Forms\Set;

class KaryawanResource extends Resource
{
    protected static ?string $model = Karyawan::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Data Karyawan';
    protected static ?string $pluralLabel = 'Karyawan';
    protected static ?string $navigationGroup = 'Manajemen Data';

    public static function form(Form $form): Form
    {
       return $form->schema([
            TextInput::make('id_karyawan')
                ->label('ID Karyawan')
                ->default(fn () => Karyawan::generateNextIdKaryawan())
                ->disabled()
                ->dehydrated(true)
                ->required()
                ->maxLength(20),

            TextInput::make('nama')
                ->label('Nama')
                ->required()
                ->maxLength(100),

            Select::make('status')
                ->label('Status')
                ->options([
                    'harian tetap' => 'Harian Tetap',
                    'harian lepas' => 'Harian Lepas',
                ])
                ->default('harian tetap')
                ->required()
                ->live()
                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                    if ($state === 'harian tetap') {
                        $set('gaji_harian', null);

                        $gajiSetengahBulan = (float) ($get('gaji_setengah_bulan') ?? 0);

                        $set(
                            'gaji_lembur',
                            $gajiSetengahBulan > 0
                                ? round(($gajiSetengahBulan * 2) / 174, 0)
                                : null
                        );
                    }

                    if ($state === 'harian lepas') {
                        $set('gaji_setengah_bulan', null);

                        $gajiHarian = (float) ($get('gaji_harian') ?? 0);
                        $jumlahHariKerjaSebulan = 25;

                        $set(
                            'gaji_lembur',
                            $gajiHarian > 0
                                ? round(($gajiHarian * $jumlahHariKerjaSebulan) / 174, 0)
                                : null
                        );
                    }
                }),

            Select::make('bagian')
                ->label('Bagian')
                ->options([
                    'logistik' => 'Logistik',
                    'material' => 'Material',
                    'mechanic electric' => 'Mechanic Electric',
                    'helper mechanic electric' => 'Helper Mechanic Electric',
                    'helper mechanic equipment' => 'Helper Mechanic Equipment',
                    'fitter' => 'Fitter',
                    'co fitter' => 'Co Fitter',
                    'helper fitter' => 'Helper Fitter',
                    'erector' => 'Erector',
                    'kepala komponen' => 'Kepala Komponen',
                    'komponen' => 'Komponen',
                    'painter' => 'Painter',
                    'qc' => 'QC',
                    'kepala qc' => 'Kepala QC',
                ])
                ->searchable()
                ->required(),

            Select::make('lokasi')
                ->label('Lokasi')
                ->options([
                    'workshop' => 'Workshop',
                    'proyek' => 'Proyek',
                ])
                ->required()
                ->reactive()
                ->afterStateUpdated(fn ($state, Set $set) => $state !== 'proyek' ? $set('jenis_proyek', null) : null),

            Select::make('jenis_proyek')
                ->label('Jenis Proyek')
                ->options([
                    'dbl' => 'DBL',
                    'parisindo' => 'Parisindo',
                    'kolon ina' => 'Kolon Ina',
                    'duta indah' => 'Duta Indah',
                    'yamatogawa' => 'Yamatogawa',
                    'pesona alam' => 'Pesona Alam',
                    'eastvara' => 'Eastvara',
                    'indo deli' => 'Indo Deli',
                    'cgs' => 'CGS',
                ])
                ->searchable()
                ->preload()
                ->visible(fn (Get $get) => $get('lokasi') === 'proyek')
                ->required(fn (Get $get) => $get('lokasi') === 'proyek'),

            TextInput::make('gaji_setengah_bulan')
                ->label('Gaji Setengah Bulan')
                ->numeric()
                ->visible(fn (Get $get) => $get('status') === 'harian tetap')
                ->required(fn (Get $get) => $get('status') === 'harian tetap')
                ->live(debounce: 500)
                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                    if ($get('status') === 'harian tetap') {
                        $gajiSetengahBulan = (float) ($state ?? 0);

                        $set(
                            'gaji_lembur',
                            $gajiSetengahBulan > 0
                                ? round(($gajiSetengahBulan * 2) / 174, 0)
                                : null
                        );
                    }
                }),

            TextInput::make('gaji_harian')
                ->label('Gaji Harian')
                ->numeric()
                ->visible(fn (Get $get) => $get('status') === 'harian lepas')
                ->required(fn (Get $get) => $get('status') === 'harian lepas')
                ->live(debounce: 500)
                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                    if ($get('status') === 'harian lepas') {
                        $gajiHarian = (float) ($state ?? 0);
                        $jumlahHariKerjaSebulan = 25;

                        $set(
                            'gaji_lembur',
                            $gajiHarian > 0
                                ? round(($gajiHarian * $jumlahHariKerjaSebulan) / 174, 0)
                                : null
                        );
                    }
                }),

            TextInput::make('gaji_lembur')
                ->label('Nominal Lembur per Jam')
                ->numeric()
                ->disabled()
                ->dehydrated(true)
                ->helperText(function (Get $get) {
                    if ($get('status') === 'harian lepas') {
                        return 'Otomatis: gaji bulanan ÷ 174 jam.';
                    }

                    return 'Otomatis: gaji bulanan ÷ 174 jam.';
                }),

            TextInput::make('uang_makan_lembur_malam')
                ->label('Uang Makan Lembur Malam')
                ->numeric()
                ->default(15000)
                ->disabled()
                ->dehydrated(true),

            TextInput::make('uang_makan_lembur_jalan')
                ->label('Uang Makan Lembur Jalan')
                ->numeric()
                ->default(25000)
                ->disabled()
                ->dehydrated(true),

            Select::make('jenis_bpjs')
                ->label('Jenis BPJS')
                ->placeholder('Pilih Jenis BPJS')
                ->options([
                    'tanpa_bpjs' => 'Tanpa BPJS',
                    'bpjs_kesehatan' => 'BPJS Kesehatan',
                    'bpjs_tenaga_kerja' => 'BPJS Tenaga Kerja',
                    'bpjs_kesehatan_tk' => 'BPJS Kesehatan + TK',
                ])
                ->searchable()
                ->preload()
                ->required()
                ->live()
                ->afterStateUpdated(function ($state, Set $set) {
                    foreach (Karyawan::hitungPotonganBpjs($state) as $field => $value) {
                        $set($field, $value);
                    }
                }),

            TextInput::make('potongan_bpjs_kesehatan')
                ->label('Potongan BPJS Kesehatan')
                ->numeric()
                ->default(0)
                ->disabled()
                ->dehydrated(true)
                ->visible(fn (Get $get) => $get('jenis_bpjs') === 'bpjs_kesehatan')
                ->helperText('Otomatis: 1% × UMR.'),

            TextInput::make('potongan_tenaga_kerja')
                ->label('Potongan BPJS Tenaga Kerja')
                ->numeric()
                ->default(0)
                ->disabled()
                ->dehydrated(true)
                ->visible(fn (Get $get) => $get('jenis_bpjs') === 'bpjs_tenaga_kerja')
                ->helperText('Otomatis: 3% × UMR.'),

            TextInput::make('potongan_bpjs_kesehatan_tk')
                ->label('Potongan BPJS Kesehatan + TK')
                ->numeric()
                ->default(0)
                ->disabled()
                ->dehydrated(true)
                ->visible(fn (Get $get) => $get('jenis_bpjs') === 'bpjs_kesehatan_tk')
                ->helperText('Otomatis: BPJS Kesehatan + BPJS TK.'),

            TextInput::make('faktor_sj')
                ->label('Faktor Senin s/d Jumat')
                ->numeric()
                ->step('0.1')
                ->default(1.5)
                ->disabled()
                ->dehydrated(true),

            TextInput::make('faktor_sabtu')
                ->label('Faktor Sabtu')
                ->numeric()
                ->step('0.1')
                ->default(1.5)
                ->disabled()
                ->dehydrated(true),

            TextInput::make('faktor_minggu')
                ->label('Faktor Minggu')
                ->numeric()
                ->step('0.1')
                ->default(2)
                ->disabled()
                ->dehydrated(true),

            TextInput::make('faktor_hari_besar')
                ->label('Faktor Hari Besar')
                ->numeric()
                ->step('0.1')
                ->default(2)
                ->disabled()
                ->dehydrated(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_karyawan')
                    ->label('ID Karyawan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->sortable(),

                TextColumn::make('bagian')
                    ->label('Bagian')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('lokasi')
                    ->label('Lokasi')
                    ->sortable(),

                TextColumn::make('jenis_proyek')
                    ->label('Jenis Proyek')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($state, $record) {
                        // pastikan 'proyek' persis (enum kamu pakai lowercase)
                        if (strtolower((string) $record->lokasi) !== 'proyek') {
                            return '-';
                        }

                        // tampilkan apa adanya tapi rapi (trim)
                        $val = is_string($state) ? trim($state) : $state;

                        return $val && $val !== '' ? $val : '-';
                    }),

                TextColumn::make('gaji_setengah_bulan')
                    ->label('Gaji Setengah Bulan')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),

                TextColumn::make('gaji_lembur')
                    ->label('Gaji Lemburs')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),

                TextColumn::make('gaji_harian')
                    ->label('Gaji Harian')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),
                
                TextColumn::make('uang_makan_lembur_malam')
                    ->label('Uang Makan Lembur Malam')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),

                TextColumn::make('uang_makan_lembur_jalan')
                    ->label('Uang Makan Lembur Jalan')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),

                TextColumn::make('potongan_bpjs_kesehatan')
                    ->label('Potongan BPJS Kesehatan')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),

                TextColumn::make('potongan_tenaga_kerja')
                    ->label('Potongan Tenaga Kerja')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),

                TextColumn::make('potongan_bpjs_kesehatan_tk')
                    ->label('Potongan BPJS Kesehatan + TK')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),
                TextColumn::make('faktor_sj')->label('Faktor SJ'),
                TextColumn::make('faktor_sabtu')->label('Faktor Sabtu'),
                TextColumn::make('faktor_minggu')->label('Faktor Minggu'),
                TextColumn::make('faktor_hari_besar')->label('Faktor Hari Besar'),
            ])
            ->filters([
            SelectFilter::make('lokasi')
                ->label('Lokasi')
                ->options(
                    Karyawan::query()
                        ->whereNotNull('lokasi')
                        ->distinct()
                        ->pluck('lokasi', 'lokasi')
                        ->toArray()
                ),
            SelectFilter::make('status')
                ->label('Status')
                ->options(
                    Karyawan::query()
                        ->whereNotNull('status')
                        ->distinct()
                        ->pluck('status', 'status')
                        ->toArray()
                ),
            SelectFilter::make('jenis_proyek')
                ->label('Proyek')
                ->options(
                    Karyawan::query()
                        ->whereNotNull('jenis_proyek')
                        ->distinct()
                        ->pluck('jenis_proyek', 'jenis_proyek')
                        ->toArray()
                )
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
            
            
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKaryawans::route('/'),
            'create' => Pages\CreateKaryawan::route('/create'),
            'edit' => Pages\EditKaryawan::route('/{record}/edit'),
        ];
    }
    public static function canViewAny(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user && $user->can('view_any_karyawan');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }

    public static function canCreate(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user && $user->can('create_karyawan');
    }

    public static function canEdit($record): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user && $user->can('update_karyawan');
    }

    public static function canDelete($record): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user && $user->can('delete_karyawan');
    }

}
