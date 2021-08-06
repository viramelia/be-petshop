<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JnsProduk extends Model
{
    use HasFactory;

    protected $table = 'jns_produk';

    protected $fillable = ['jenis_produk'];
}
