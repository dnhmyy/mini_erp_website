<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterProduk extends Model
{
    protected $fillable = ['kode_produk', 'nama_produk', 'satuan', 'kategori', 'target_role'];

    protected $casts = [
        'target_role' => 'array',
    ];

    public function permintaanDetails()
    {
        return $this->hasMany(PermintaanDetail::class, 'produk_id');
    }

    public function cabangs()
    {
        return $this->belongsToMany(Cabang::class, 'master_produk_cabang', 'master_produk_id', 'cabang_id');
    }
}
