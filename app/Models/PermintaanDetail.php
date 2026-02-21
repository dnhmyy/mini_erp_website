<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanDetail extends Model
{
    protected $fillable = ['permintaan_id', 'produk_id', 'qty', 'qty_dikirim', 'qty_terima'];

    public function permintaan()
    {
        return $this->belongsTo(Permintaan::class);
    }

    public function produk()
    {
        return $this->belongsTo(MasterProduk::class, 'produk_id');
    }
}
