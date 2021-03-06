<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'role',
        'nama_lengkap',
        'alamat',
        'no_hp',
        'foto',
        'tgl_lahir',
        'gender',
        'jam_buka',
        'jam_tutup',
        'nama_bank',
        'no_rek',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function produk(){
        return $this->hasMany(Produk::class);
    }

    public function pesananProduk(){
        return $this->hasMany(PesananProduk::class);
    }

    public function layanan(){
        return $this->hasMany(Layanan::class);
    }

    public function transaksiPetshop(){
        return $this->hasMany(Transaksi::class);
    }

    public function bookingPetshop(){
        return $this->hasMany(Layanan::class, 'booking_layanan', 'id_petshop', 'id_layanan');
    }

    public function bookingCustomer(){
        return $this->hasMany(BookingLayanan::class, 'id_customer');
    }

    public function hari(){
        return $this->belongsToMany(Hari::class, 'hari_petshop', 'id_petshop', 'id_hari');
    }
}
