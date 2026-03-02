<?php

namespace App\Filament\Admin\Pages;

use App\Models\KasbonRequest;
use App\Models\Karyawan;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class KasbonPengajuanStaffKasbon extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-document-plus';
    protected static ?string $navigationLabel = 'Pengajuan Kasbon';
    protected static ?string $title           = 'Pengajuan Data Kasbon';
    protected static ?string $navigationGroup = 'Kasbon';
    protected static ?int $navigationSort     = 2;

    protected static string $view = 'filament.pages.kasbon-pengajuan-staff-kasbon';

    public ?KasbonRequest $record = null;

    public array $data = [];

    // ✅ HRBAC UI
    public static function canAccess(): bool
    {
        return Gate::allows('kasbon.process');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false; // ✅ biar tidak muncul menu, akses dari tombol NEW DATA saja
    }

    public function mount(): void
    {
        $id = request('id');

        if ($id) {
            $this->record = KasbonRequest::findOrFail($id);

            $this->form->fill([
                'karyawan_id'       => $this->record->karyawan_id,
                'tanggal_pengajuan' => $this->record->tanggal_pengajuan,
                'nominal'           => $this->record->nominal,
                'tenor'             => $this->record->tenor,
                'cicilan'           => $this->record->cicilan,
                'alasan_pengajuan'  => $this->record->alasan_pengajuan,
                'prioritas'         => $this->record->prioritas,
                'catatan_staff'     => $this->record->catatan_staff,
            ]);
        } else {
            $this->form->fill([
                'tanggal_pengajuan' => now(),
                'tenor' => 1,
            ]);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Section::make('Pengajuan Data Kasbon')
                    ->schema([

                        Forms\Components\Select::make('karyawan_id')
                            ->label('Karyawan')
                            ->relationship('karyawan', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\DatePicker::make('tanggal_pengajuan')
                            ->label('Tanggal')
                            ->required()
                            ->default(now()),

                        Forms\Components\TextInput::make('nominal')
                            ->label('Pokok Kasbon')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $tenor = (int) ($get('tenor') ?: 1);
                                if ($state && $tenor > 0) {
                                    $set('cicilan', ceil(((float)$state) / $tenor));
                                }
                            }),

                        Forms\Components\TextInput::make('tenor')
                            ->label('Tenor (X kali)')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->reactive()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $nominal = (float) ($get('nominal') ?: 0);
                                $tenor   = max(1, (int) $state);
                                if ($nominal > 0) {
                                    $set('cicilan', ceil($nominal / $tenor));
                                }
                            }),

                        Forms\Components\TextInput::make('cicilan')
                            ->label('Cicilan per Periode')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated() // tetap disimpan ke DB
                            ->required(),

                        Forms\Components\Textarea::make('alasan_pengajuan')
                            ->label('Alasan Pengajuan')
                            ->rows(3)
                            ->required(),

                        Forms\Components\Select::make('prioritas')
                            ->label('Prioritas (Opsional)')
                            ->options([
                                'rendah' => 'Rendah',
                                'sedang' => 'Sedang',
                                'tinggi' => 'Tinggi',
                            ])
                            ->helperText('Prioritas biasanya diisi setelah DO approve tahap awal.'),

                        Forms\Components\TextInput::make('catatan_staff')
                            ->label('Catatan Staff')
                            ->maxLength(255)
                            ->placeholder('Opsional: catatan dari staff kasbon'),
                    ])
                    ->columns(2),
            ]);
    }

    // ✅ tombol simpan draft
    public function saveDraft(): void
    {
        $data = $this->form->getState();

        if ($this->record) {
            $this->record->update($data);
        } else {
            $this->record = KasbonRequest::create([
                ...$data,
                'status_awal' => 'draft',
                'status_akhir' => 'draft',
            ]);
        }

        Notification::make()
            ->title('Draft berhasil disimpan')
            ->success()
            ->send();

        $this->redirect(route('filament.admin.pages.kasbon-pinjaman-staff-kasbon'));
    }

    // ✅ tombol kirim ke DO (langsung masuk antrian)
    public function submitToDO(): void
    {
        $data = $this->form->getState();

        if ($this->record) {
            $this->record->update([
                ...$data,
                'status_awal' => 'waiting_do_awal',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);
        } else {
            $this->record = KasbonRequest::create([
                ...$data,
                'status_awal' => 'waiting_do_awal',
                'status_akhir' => 'draft',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);
        }

        Notification::make()
            ->title('Berhasil dikirim ke Direktur Operasional')
            ->success()
            ->send();

        $this->redirect(route('filament.admin.pages.kasbon-pinjaman-staff-kasbon'));
    }
}
