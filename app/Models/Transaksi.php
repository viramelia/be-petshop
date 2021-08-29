<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = ['id_petshop', 'id_customer', 'id_admin',
                            'jenis_transaksi', 'total_harga', 'bukti_tf', 'status'];

    public function pesanan(){
        return $this->hasMany(PesananProduk::class, 'id_transaksi');
    }

    public function customer(){
        return $this->belongsTo(User::class, 'id_customer');
    } 

    public function petshop(){
        return $this->belongsTo(User::class, 'id_petshop');
    }
}
