<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingLayanan extends Model
{
    use HasFactory;

    protected $table = "booking_layanan";

    protected $fillable = ['id_petshop', 'id_customer', 'id_layanan', 'tgl_booking', 
                            'jam_mulai', 'jam_selesai', 'jenis_transaksi', 'jenis_hewan',
                            'berat_hewan', 'status', 'biaya'];
    
    public function petshop(){
        return $this->belongsTo(User::class, 'id_petshop');
    }

    public function customer(){
        return $this->belongsTo(User::class, 'id_customer');
    }

    public function layanan(){
        return $this->belongsTo(Layanan::class, 'id_layanan');
    }
}
