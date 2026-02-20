<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'cabang_id',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function permintaans()
    {
        return $this->hasMany(Permintaan::class);
    }

    public function isSuperUser()
    {
        return $this->role === 'superuser';
    }

    public function isStaffGudang()
    {
        return $this->role === 'staff_gudang';
    }

    public function isStaffAdmin()
    {
        return $this->role === 'staff_admin';
    }

    public function isStaffProduksi()
    {
        return $this->role === 'staff_produksi';
    }

    public function isStaffDapur()
    {
        return $this->role === 'staff_dapur';
    }

    public function isStaffPastry()
    {
        return $this->role === 'staff_pastry';
    }

    public function isBranchLevel()
    {
        return in_array($this->role, ['staff_admin', 'staff_produksi', 'staff_dapur', 'staff_pastry']);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
