<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananProduk extends Model
{
    use HasFactory;

    protected $table = 'pesanan_produk';

    protected $fillable = ['id_petshop', 'id_produk', 'id_transaksi', 'id_customer',
                            'jumlah_pesanan', 'waktu_pemesanan'];

    public function petshop(){
        return $this->belongsTo(User::class, 'id_petshop');
    }

    public function produk(){
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function transaksi(){
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }
}
