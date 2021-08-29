<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $table = "layanan";

    protected $fillable = ['id_petshop', 'nama', 'kategori', 'gambar', 'deskripsi', 'jenis_hewan',
                            'biaya_layanan'];

    public function petshop(){
        return $this->belongsTo(User::class,'id_petshop');
    }

    public function customer(){
        return $this->belongsTo(User::class, 'id_customer');
    }

    public function booking(){
        return $this->hasMany(BookingLayanan::class, 'id_layanan');
    }
}
