<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PenilaianKinerjaResource\Pages;
use App\Models\Karyawan;
use App\Models\PenilaianKinerja;
use App\Services\PenilaianKinerjaService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Filament\Forms\Get;
use Filament\Forms\Set;

class PenilaianKinerjaResource extends Resource
{
    protected static ?string $model = PenilaianKinerja::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Penilaian Kinerja';
    protected static ?string $pluralLabel = 'Penilaian Kinerja';
    protected static ?string $navigationGroup = 'Manajemen Data';
    protected static ?int    $navigationSort  = 2;

    public static function form(Form $form): Form
    {
        $opsiNilai = [
            'A' => 'A',
            'B' => 'B',
            'C' => 'C',
            'D' => 'D',
            'E' => 'E',
        ];

        $hitungNilai = function (Get $get, Set $set) {
            $service = app(PenilaianKinerjaService::class);

            $hasil = $service->hitung([
                'disiplin' => $get('disiplin'),
                'tanggung_jawab' => $get('tanggung_jawab'),
                'kualitas_kerja' => $get('kualitas_kerja'),
                'produktivitas' => $get('produktivitas'),
                'kerja_sama' => $get('kerja_sama'),
                'inisiatif' => $get('inisiatif'),
            ]);

            $set('nilai_akhir', $hasil['nilai_akhir']);
            $set('predikat', $hasil['predikat']);

            if (blank($get('nominal_kenaikan_gaji')) || (int) $get('nominal_kenaikan_gaji') === 0) {
                $set('nominal_kenaikan_gaji', $hasil['nominal_kenaikan_gaji']);
            }
        };

        return $form->schema([
            Select::make('karyawan_id')
                ->label('Karyawan')
                ->relationship('karyawan', 'nama')
                ->searchable()
                ->preload()
                ->required(),

            TextInput::make('periode_kenaikan_gaji')
                ->label('Periode Kenaikan Gaji')
                ->required(),

            DatePicker::make('tanggal_penilaian')
                ->label('Tanggal Penilaian')
                ->required()
                ->default(now()),

            Select::make('disiplin')
                ->label('Disiplin')
                ->options($opsiNilai)
                ->required()
                ->live()
                ->afterStateUpdated($hitungNilai),

            Select::make('tanggung_jawab')
                ->label('Tanggung Jawab')
                ->options($opsiNilai)
                ->required()
                ->live()
                ->afterStateUpdated($hitungNilai),

            Select::make('kualitas_kerja')
                ->label('Kualitas Kerja')
                ->options($opsiNilai)
                ->required()
                ->live()
                ->afterStateUpdated($hitungNilai),

            Select::make('produktivitas')
                ->label('Produktivitas')
                ->options($opsiNilai)
                ->required()
                ->live()
                ->afterStateUpdated($hitungNilai),

            Select::make('kerja_sama')
                ->label('Kerja Sama')
                ->options($opsiNilai)
                ->required()
                ->live()
                ->afterStateUpdated($hitungNilai),

            Select::make('inisiatif')
                ->label('Inisiatif')
                ->options($opsiNilai)
                ->required()
                ->live()
                ->afterStateUpdated($hitungNilai),

            TextInput::make('nilai_akhir')
                ->label('Nilai Akhir')
                ->numeric()
                ->readOnly()
                ->dehydrated(),

            TextInput::make('predikat')
                ->label('Predikat')
                ->readOnly()
                ->dehydrated(),

            TextInput::make('nominal_kenaikan_gaji')
                ->label('Nominal Kenaikan Gaji')
                ->numeric()
                ->prefix('Rp')
                ->default(0),

            Textarea::make('catatan')
                ->label('Catatan')
                ->rows(3),
        ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $hasil = app(PenilaianKinerjaService::class)->hitung($data);

        $data['nilai_akhir'] = $hasil['nilai_akhir'];
        $data['predikat'] = $hasil['predikat'];
        $data['nominal_kenaikan_gaji'] = $hasil['nominal_kenaikan_gaji'];
        $data['penilai_user_id'] = Auth::id();

        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        $hasil = app(PenilaianKinerjaService::class)->hitung($data);

        $data['nilai_akhir'] = $hasil['nilai_akhir'];
        $data['predikat'] = $hasil['predikat'];
        $data['nominal_kenaikan_gaji'] = $hasil['nominal_kenaikan_gaji'];
        $data['penilai_user_id'] = Auth::id();

        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('karyawan.nama')
                    ->label('Karyawan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('periode_kenaikan_gaji')
                    ->label('Periode')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tanggal_penilaian')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('nilai_akhir')
                    ->label('Nilai Akhir')
                    ->sortable(),

                TextColumn::make('predikat')
                    ->label('Predikat')
                    ->badge(),

                TextColumn::make('nominal_kenaikan_gaji')
                    ->label('Kenaikan Gaji')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
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
            'index' => Pages\ListPenilaianKinerjas::route('/'),
            'create' => Pages\CreatePenilaianKinerja::route('/create'),
            'edit' => Pages\EditPenilaianKinerja::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Gate::allows('kinerja.manage');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }

    public static function canCreate(): bool
    {
        return Gate::allows('kinerja.manage');
    }

    public static function canEdit($record): bool
    {
        return Gate::allows('kinerja.manage');
    }

    public static function canDelete($record): bool
    {
        return Gate::allows('kinerja.manage');
    }
}