<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = "produk";

    protected $fillable = ['id_petshop','id_jns_produk','nama', 'foto', 'deskripsi', 'tgl_masuk', 
                            'expire', 'stok_produk', 'harga_satuan_produk'];

    public function user(){
        return $this->belongsTo(User::class, 'id_petshop');
    }
}
