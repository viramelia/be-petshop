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

    public function user(){
        return $this->belongsTo(User::class, 'id_petshop');
    }

    public function booking(){
        return $this->hasMany(Layanan::class, 'id_layanan');
    }
}
