<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{
    protected $fillable = [
        'no_request',
        'kategori',
        'cabang_id',
        'user_id',
        'status',
        'tanggal',
        'driver'
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(PermintaanDetail::class);
    }
}
