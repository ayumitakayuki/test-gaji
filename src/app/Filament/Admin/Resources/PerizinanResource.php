<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PerizinanResource\Pages;
use App\Filament\Admin\Resources\PerizinanResource\RelationManagers;
use App\Models\Perizinan;
use Filament\Forms;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Hidden;
use App\Models\User;

class PerizinanResource extends Resource
{
    protected static ?string $model = Perizinan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Conditionally show the karyawan selector:
            Select::make('karyawan_id')
                ->relationship('karyawan', 'nama')
                ->label('Karyawan')
                ->hidden(fn () => Auth::user()?->role === 'karyawan')
                ->disabled(fn () => Auth::user()?->role === 'karyawan')
                ->required(fn () => Auth::user()?->role !== 'karyawan'),

            Hidden::make('karyawan_id')
                ->default(fn () => Auth::user()?->karyawan_id)
                ->dehydrated()
                ->hidden(fn () => Auth::user()?->role !== 'karyawan'),

            Forms\Components\Select::make('jenis')
                ->options([
                    'sakit'        => 'Sakit (dengan surat dokter)',
                    'berduka'      => 'Berduka',
                    'tanpa_alasan' => 'Tanpa Alasan',
                ])
                ->label('Jenis Izin')
                ->required(),
            Forms\Components\DatePicker::make('tanggal_mulai')->label('Tanggal Mulai')->required(),
            Forms\Components\DatePicker::make('tanggal_selesai')->label('Tanggal Selesai')->required(),
            Forms\Components\Textarea::make('keterangan')->label('Keterangan'),
            Forms\Components\FileUpload::make('bukti_path')
                ->label('Upload Surat/Bukti')
                ->directory('bukti-perizinan')
                ->acceptedFileTypes(['application/pdf', 'image/*']),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('karyawan.nama')->label('Nama'),
                TextColumn::make('jenis')->label('Jenis'),
                TextColumn::make('tanggal_mulai')->label('Mulai')->date(),
                TextColumn::make('tanggal_selesai')->label('Selesai')->date(),
                TextColumn::make('keterangan')
                ->label('Keterangan')
                ->wrap() 
                ->limit(50),
                IconColumn::make('is_approved')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning'),
            ])
            ->actions([
            Tables\Actions\Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (Perizinan $record) => ! $record->is_approved)
                ->action(function (Perizinan $record) {
                    $record->update([
                        'is_approved' => true,
                        'approved_by' => Auth::id(),
                        'approved_at' => now(),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPerizinans::route('/'),
            'create' => Pages\CreatePerizinan::route('/create'),
            'edit' => Pages\EditPerizinan::route('/{record}/edit'),
        ];
    }
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
