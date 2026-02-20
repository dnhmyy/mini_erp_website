<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    protected $fillable = ['nama', 'alamat'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permintaans()
    {
        return $this->hasMany(Permintaan::class);
    }

    public function masterProduks()
    {
        return $this->belongsToMany(MasterProduk::class, 'master_produk_cabang', 'cabang_id', 'master_produk_id');
    }
}
