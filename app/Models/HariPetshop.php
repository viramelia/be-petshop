<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HariPetshop extends Model
{
    use HasFactory;

    protected $table = 'hari_petshop';

    protected $fillable = ['id_petshop', 'id_hari'];

    // public function petshop(){
    //     return $this->belongsToMany(User::class, 'id_petshop');
    // }

    // public function hari(){
    //     return $this->belongsToMany(Hari::class, 'id_hari');
    // }
}
