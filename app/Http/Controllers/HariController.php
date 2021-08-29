<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HariPetshop;
use App\Models\Hari;
use Validator;

class HariController extends Controller
{
    public function allHari(){
        $data = Hari::all();

        return response()->json([
            'message' => 'succeedd get data hari',
            'data' => $data
        ], 200);
    }

    public function pilihHari(Request $request){
        $rules = array(
            'id_hari' => 'required'
        );

        $validated = Validator::make($request->all(), $rules);
    
        if($validated->fails()){
            return $validated->errors();
        }
        else{
            $data = HariPetshop::create([
                'id_petshop' => $request->id,
                'id_hari' => $request->id_hari
            ]);
            return response()->json([
                'message' => 'succedd select hari'
            ], 201);
        }
    }

    public function deleteHari(Request $request){
        $hariBefore = HariPetshop::where('id_petshop', $request->id);
        $hariBefore->delete();

        return response()->json([
            'message' => 'succedd deleted hari'
        ], 200);
    } 

    public function hariPetshop($petshop){
        $data = hariPetshop::where('id_petshop', $petshop)->get();
        $data->load('hari');
    
        return response()->json([
             'hari' => $data 
         ], 200);
    }
}
