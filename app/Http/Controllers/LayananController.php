<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Layanan;
use App\Models\User;

class LayananController extends Controller
{
    
    public function createLayanan(Request $request){
        $user = User::find($request->id);

        if($user->role == 'petshop'){
            $rules = array(
                'nama'=> 'required|min: 4',
                'kategori'=> 'required',
                'gambar'=> 'required',
                'deskripsi'=> 'required',
                'jenis_hewan'=> 'required',
                'biaya_layanan'=> 'required|integer'
            );
            
            $validated = Validator::make($request->all(), $rules);
    
            if($validated->fails()){
                return $validated->errors();
            }
            else{
                $namaFoto = $request->nama.time().'.'.$request->file('gambar')->extension();
                $foto = $request->file('gambar')
                        ->storeAs('public/layanan', $namaFoto);
                $layanan = Layanan::create([
                    'id_petshop'=> $user->id,
                    'nama'=> $request->nama,
                    'kategori'=> $request->kategori,
                    'gambar'=> $namaFoto,
                    'deskripsi'=> $request->deskripsi,
                    'jenis_hewan'=> $request->jenis_hewan,
                    'biaya_layanan'=> $request->biaya_layanan,
                ]);
                
                return response()->json([
                    'message' => 'Succedd to input layanan',
                    'data' => $layanan,
                ], 201);
            }
        }
        else{
            return response()->json(['message'=> 'Oops...not your authorize'], 400);
        }
    }

    public function allLayanan($jumlah){
        $data = Layanan::paginate($jumlah);

        return response()->json([
            'message' => 'All data success to get',
            'data' => $data,
        ], 200);
    }

    public function layananById(Request $request){
        $data = Layanan::find($request->id);

        if($data){
            $data->load('petshop');
            return response()->json([
                'message' => 'succedd to get layanan',
                'data' => $data
            ], 200);
        }
        else{
            return response()->json([
                'message'=> 'Oops...layanan not found',
            ], 404);
        }
    }

    public function layananByPetshop($petshop){
        $data = Layanan::where('id_petshop', '=', $petshop)->paginate(4);
        $data->load('petshop');

        return response()->json([
            'message' => 'succedd get layanan petshop',
            'data' => $data
        ], 200);
    }

    public function cariLayanan($layanan){
        $data = Layanan::where('nama', 'LIKE', '%'.$layanan.'%')->get();

        return response()->json([
            'data' => $data
        ], 200);
    }

    public function image($fileName){
        $tes = asset('storage/layanan/'.$fileName);
        return response()->json([
            'gambar' => $tes
        ], 200);
    }
}
