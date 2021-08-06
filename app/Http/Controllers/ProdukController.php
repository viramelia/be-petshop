<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\JnsProduk;
use App\Models\Produk;

class ProdukController extends Controller
{
    public function createJnsProduk(Request $request){
        $user = User::findOrFail($request->id_user);
        if($user->role == 'admin'){
            $rules = array(
                'jenis_produk' => 'required|unique:jns_produk,jenis_produk',
            );
    
            $validated = Validator::make($request->all(), $rules);
    
            if($validated->fails()){
                return $validated->errors();
            }
            else{
                $jenis = JnsProduk::create([
                    'jenis-produk' => $request->jenis_produk,
                ]);
    
                return response()->json([
                    'message' => 'jenis produk berhasil ditambahkan'
                ], 201);
            }
        }
        else{
            return response()->json([
                'message'=> 'not your access'
            ], 401);
        }
    }

    public function createProduk(Request $request){
        $user = User::findOrFail($request->id_petshop);

        if($user->role == 'petshop'){
            $rules = array(
                'nama' => 'required|min:3',
                'foto' => 'required',
                'deskripsi' => 'required',
                'tgl_masuk' => 'required|date_format:Y-m-d',
                'expire' => 'required|date_format:Y-m-d',
                'stok_produk' => 'required|integer',
                'harga_satuan-produk' => 'requred|integer',
            );

            $validated = Validator::make($request->all(), $rules);
    
            if($validated->fails()){
                return $validated->errors();
            }
            else{
                $namaFoto = $request->nama.time().'.'.$request->file('foto')->extension();
                $foto = $request->file('foto')
                        ->storeAs('produk', $namaFoto);
                $produk = Produk::create([
                    'id_petshop' => $user->id,
                    'id_jns_produk' => $request->id_jns_produk,
                    'nama' => $request->nama,
                    'foto' => $namaFoto,
                    'deskripsi' => $request->deskripsi,
                    'tgl_masuk' => $request->tgl_masuk,
                    'expire' => $request->expire,
                    'stok_produk' => $request->stok_produk,
                    'harga_satuan_produk' => $request->harga_satuan_produk,
                ]);
                return response()->json([
                    'message' => 'produk berhasil ditambahkan',
                    'data'=> $produk,
                ], 200);
            }
        }
        else{
            return response()->json(['message'=> 'Oops...bot our authorize'], 400);
        }
    }
}
