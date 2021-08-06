<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $table = "layanan";

    protected $fillable = ['nama', 'kategori', 'gambar', 'deskripsi', 'jenis_hewan',
                            'biaya_layanan', 'bukti_tf', 'berat_hewan'];
}
