<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Produk;
use App\Models\HariPetshop;
use Validator;
use Hash;
use App\Mail\MyTestMail;
use Illuminate\Support\Facades\Mail;

class PetshopController extends Controller
{
    public function allPetshop(){
        $data = User::where([['role', '=', 'petshop'],
                            ['status', '=', 'aktif']])->paginate(10);
        return response()->json([
            'data'=> $data
        ], 200);
    }

    public function allPetshopName(){
        $data = User::where([['role', '=', 'petshop'],
                            ['status', '=', 'aktif']])->get('nama_lengkap');

        return response()->json([
            'data'=> $data
        ], 200);
    }
    
    public function allCustomer(){
        $data = User::where('role', 'customer')->paginate(10);
        return response()->json([
            'data'=> $data
        ], 200);
    }

    public function petshopToConfirm(){
        $data = User::where([['role', '=', 'petshop'],
                            ['status', '=', 'non']])->get();
        
        return response()->json([
            'message' => 'all petshop to confirm',
            'data' => $data
        ], 200);
    }

    public function petshopDetail(Request $request){
        $data = User::find($request->id);
        $hari = HariPetshop::where('id_petshop', '=', $request->id);

        return response()->json([
            'message' => 'succedd get petshop detail',
            'data' => $data,
            'hari' => $hari,
        ], 200);
    }
    
    public function verifPetshop(Request $request){
        
        $petshop = User::find($request->id);
        $angka = rand(1000, 9000);
        if($petshop->role == 'petshop'){
            $password = Hash::make($angka);
            $petshop->status = 'aktif';
            $petshop->password = $password;
            $petshop->save();
            
            $details = [
            'title' => 'Akun petshop anda telah aktif',
            'body' => 'Gunakan akun anda dan password berikut untuk login '.$angka
            ];
            
            Mail::to($petshop->email)->send(new \App\Mail\MyTestMail($details));
            
            return response()->json([
                'message' => 'Petshop telah aktif',
                'password' => $angka
            ], 200);
        }
        else{
            return response()->json([
                'message'=> 'Oops...not petshop'
            ], 400);
        }
    }
}
