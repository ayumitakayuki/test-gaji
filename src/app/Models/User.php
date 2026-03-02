<?php

namespace App\Models;

use App\Traits\EnforceSoD;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Karyawan;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // ✅ Tangani collision method assignRole/syncRoles
    use HasRoles, EnforceSoD {
        EnforceSoD::assignRole insteadof HasRoles;
        EnforceSoD::syncRoles insteadof HasRoles;

        HasRoles::assignRole as spatieAssignRole;
        HasRoles::syncRoles as spatieSyncRoles;
    }

    protected $fillable = [
        'avatar_url',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function karyawan()
    {
        return $this->belongsTo(\App\Models\Karyawan::class);
    }


    public function getFilamentAvatarUrl(): ?string
    {
        if ($this->avatar_url) {
            return asset('storage/' . $this->avatar_url);
        }

        $hash = md5(strtolower(trim($this->email)));
        return 'https://www.gravatar.com/avatar/' . $hash . '?d=mp&r=g&s=250';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole([
            'super_admin',
            'staff_administrasi',
            'staff_kasbon',
            'direktur_operasional',
        ]);
    }
}
