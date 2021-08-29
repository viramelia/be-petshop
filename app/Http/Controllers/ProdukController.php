<?php

namespace App\Http\Controllers;

// use Validator;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\JnsProduk;
use App\Models\Produk;

class ProdukController extends Controller
{
    public function allProduk($jumlah){
        $data = Produk::paginate($jumlah);
        
        return response()->json([
            'message'=> 'Success get all products',
            'data'=> $data
        ], 200);
    }

    public function produkById(Request $request){
        $data = Produk::findOrFail($request->id);

        if($data){
            $data->load('petshop');
            return response()->json([
                'message' => 'success to find product',
                'data' => $data
            ], 200);
        }
        else{
            return response()->json([
                'message' => 'Oops...product not found',
            ], 404);
        }
    }

    public function produkByPetshop($petshop){
        // dd($petshop);
        $data = Produk::where('id_petshop', '=', $petshop)->paginate(4);
        
        $byPetshop = array();
        $data->load('petshop');
        // foreach($data->user as $user){
        //     array_push($byPetshop, $user);
        // }

        return response()->json([
            'message' => 'success get petshop products',
            'data' => $data
        ], 200);
    }

    public function getJnsProduk(){
        $data = JnsProduk::all();

        return response()->json([
            'message' => 'succedd get all jenis produk',
            'data' => $data
        ], 200);
    }

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
        $user = User::find($request->id);

        if($user->role == 'petshop'){
            $rules = array(
                'nama' => 'required|min:3',
                'foto' => 'required',
                'deskripsi' => 'required',
                'tgl_masuk' => 'required|date_format:Y-m-d',
                'expire' => 'required|date_format:Y-m-d',
                'stok_produk' => 'required|integer',
                'harga_satuan_produk' => 'required|integer',
            );

            $validated = Validator::make($request->all(), $rules);
    
            if($validated->fails()){
                return $validated->errors();
            }
            else{
                $namaFoto = $request->nama.time().'.'.$request->file('foto')->extension();
                $foto = $request->file('foto')
                        ->storeAs('public/produk', $namaFoto);
                // dd($user->id);
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
            return response()->json(['message'=> 'Oops...not your authorize'], 400);
        }
    }

    public function cariProduk($produk){
        // dd($produk);
        $data = Produk::where('nama', 'LIKE', '%'.$produk.'%')->get();
        return response()->json([
            'data' => $data
        ], 200);
    }

    public function image($fileName){
        $tes = asset('storage/produk/'.$fileName);
        return response()->json([
            'gambar' => $tes
        ], 200);
    }
}
