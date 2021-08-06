<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Produk;
use Validator;
use Hash;
use App\Mail\MyTestMail;
use Illuminate\Support\Facades\Mail;

class PetshopController extends Controller
{
    public function allPetshop(){
        $data = User::where('role', 'petshop')->get();
        return response()->json([
            'data'=> $data
        ], 200);
    }
    
    public function allCustomer(){
        $data = User::where('role', 'customer')->get();
        return response()->json([
            'data'=> $data
        ], 200);
    }
    
    public function verifPetshop(Request $request){
        $rules = array(
            'id_admin' => 'required',
            'id_petshop' => 'required'
        );

        $validated = Validator::make($request->all(), $rules);

        if($validated->fails()){
            return $validated->errors();
        }
        else{
            $user = User::findOrFail($request->id_admin);
    
            if($user->role == 'admin'){
                $petshop = User::findOrFail($request->id_petshop);
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
                    
                    Mail::to('60200117019@uin-alauddin.ac.id')->send(new \App\Mail\MyTestMail($details));
                    
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
            else{
                return response()->json([
                    'message' => 'Oops...not authorized'
                ], 400);
            }
        }

    }
}
