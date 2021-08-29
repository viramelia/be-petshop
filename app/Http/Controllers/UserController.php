<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaksi;
use Validator;
use Hash;

class UserController extends Controller
{

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60 * 24,
            'id'=> auth()->user()->id,
            'role'=> auth()->user()->role,
        ]);
    }

    public function regisPetshop(Request $request){
        $rules = array(
            'email' => 'required|unique:users,email',
            'nama_lengkap' => 'required|min: 4',
            'alamat' => 'required',
            'no_hp' => 'required|min:11|max:12',
            'nama_bank' => 'required',
            'no_rek' => 'required',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i',
            'foto' => 'required'
        );

        $validated = Validator::make($request->all(), $rules);

        if($validated->fails()){
            // return $validated->errors();
            return response()->json([
                'message' => 'failed'
            ], 422);
            // return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }
        else{
            $namaFoto = $request->nama_lengkap.time().'.'.$request->file('foto')->extension();
            $foto = $request->file('foto')
                    ->storeAs('public/petshop', $namaFoto);
            $user = User::create([
                'email' => $request->email,
                'role' => 'petshop',       
                'nama_lengkap' => $request->nama_lengkap,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'nama_bank' => $request->nama_bank,
                'no_rek' => $request->no_rek,
                'jam_buka' => $request->jam_buka,
                'jam_tutup' => $request->jam_tutup,
                'status' => 'non',
                'foto' => $namaFoto,
            ]);

            return response()->json([
                    'success' => true,
                    'message' => 'Silahkan tunggu admin mengkonfirmasi akun petshop anda',
                    'data' => $user->id
                ], 201);
        }
    }

    public function regisCustomer(Request $request){
        $rules = array(
            'nama_lengkap'=> 'required|min: 4',
            'email'=> 'required|unique:users,email',
            'password'=> 'required|min:6',
            'tgl_lahir'=> 'required|date_format:Y-m-d',
            'jenis_kelamin'=> 'required',
            'alamat'=> 'required',
            'no_hp'=> 'required|min: 11|max:12',
            'foto'=> 'required|mimes:jpg,png',
        );

        $validated = Validator::make($request->all(), $rules);

        if($validated->fails()){    
            return $validated->errors();
        }
        else{
            // $validated['password'] = bcrypt($validated['password']);
            // dd($request->file('foto')->extension());
            $request->password = Hash::make($request->password);
            $namaFoto = $request->nama_lengkap.time().'.'.$request->file('foto')->extension();
            $foto = $request->file('foto')
                    ->storeAs('public/customer', $namaFoto);
            $user = User::create([
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => $request->password,
                'role'=> 'customer',
                'tgl_lahir'=> $request->tgl_lahir,
                'jenis_kelamin'=> $request->jenis_kelamin,
                'alamat'=> $request->alamat,
                'no_hp'=> $request->no_hp,
                'foto'=> $namaFoto,
            ]);
            return response()->json([
                'success' => true,
                'message'=> 'Customer berhasil terdaftar',
            ], 201);
        }
    }

    public function updateCustomer(Request $request){
        $data = User::find($request->id);

        if($data){
            if($data->role == 'customer'){
                if($data->foto == $request->foto){
                    $data->update([
                        'nama_lengkap' => $request->nama_lengkap,
                        'email' => $request->email,
                        'tgl_lahir' => $request->tgl_lahir,
                        'gender' => $request->gender,
                        'alamat' => $request->alamat,
                        'no_hp' => $request->no_hp,
                    ]);
                }
                else{
                    $namaFoto = $request->nama_lengkap.time().'.'.$request->file('foto')->extension();
                    $foto = $request->file('foto')
                        ->storeAs('public/customer', $namaFoto);
                    $data->update([
                        'nama_lengkap' => $request->nama_lengkap,
                        'email' => $request->email,
                        'foto' => $namaFoto,
                        'tgl_lahir' => $request->tgl_lahir,
                        'gender' => $request->gender,
                        'alamat' => $request->alamat,
                        'no_hp' => $request->no_hp,
                    ]);

                }
    
                return response()->json([
                    'message' => 'user data updated',
                    'data' => $data
                ], 200);
            }
            else if($data->role == 'petshop'){
                if($data->foto == $request->foto){
                    $data->update([
                        'nama_lengkap' => $request->nama_lengkap,
                        'alamat' => $request->alamat,
                        'no_hp' => $request->no_hp,
                        'jam_buka' => $request->jam_buka,
                        'jam_tutup' => $request->jam_tutup,
                        'nama_bank' => $request->nama_bank,
                        'no_rek' => $request->no_rek
                    ]);

                    return response()->json([
                        'message' => 'data petshop updated'
                    ], 200);
                }
                else{
                    $namaFoto = $request->nama_lengkap.time().'.'.$request->file('foto')->extension();
                    $foto = $request->file('foto')
                        ->storeAs('public/petshop', $namaFoto);

                    $data->update([
                        'nama_lengkap' => $request->nama_lengkap,
                        'foto' => $namaFoto,
                        'alamat' => $request->alamat,
                        'no_hp' => $request->no_hp,
                        'jam_buka' => $request->jam_buka,
                        'jam_tutup' => $request->jam_tutup,
                        'nama_bank' => $request->nama_bank,
                        'no_rek' => $request->no_rek
                    ]);

                    return response()->json([
                        'message' => 'data petshop updated'
                    ], 200);
                }
            }
        }
        else{
            return response()->json([
                'message' => 'user not found'
            ], 404);
        }
    }

    // public function updatePetshop(Request $request){
    //     $data = User::find($request->id);

    //     if($data){

    //     }
    //     else{
    //         return response()->json([
    //             'message' => 'user not found'
    //         ], 404);
    //     }
    // }

    public function hariPetshop(Request $request){
        $data = User::find($request->id);
        $data->foto = asset('storage/petshop/'.$data->foto);
        $data->load('hari');
        $hariPetshop = array();
        foreach($data->hari as $hari){
            array_push($hariPetshop, $hari);
        }

        // $data->load('produk');

        // $produkPetshop = array();
        // foreach($data->produk as $produk){
        //     array_push($produkPetshop, $produk);
        // }

        // dd($hariPetshop);
        return response()->json([
            'data' => $data,
        ], 200);
    }

    public function userById(Request $request){
        $data = User::find($request->id);
        $data->load('hari');
        
        return response()->json([
            'message' => 'success get data user',
            'data' => $data
        ], 200);
    }

    public function countingAll(){
        $petshop = User::where('role', 'petshop')->get()->count();
        $customer = User::where('role', 'customer')->get()->count();
        $transaksiToConfirm = Transaksi::where('status', 'belum')->get()->count();
        $transaksiSukses = Transaksi::where('status', 'diterima')->get()->count();


        
        return response()->json([
            'message' => 'SUCCESS COUNT THE SUMMARY',
            'petshop' => $petshop,
            'customer' => $customer,
            'transaksi_waiting' => $transaksiToConfirm,
            'transaksi_sukses' => $transaksiSukses,
        ], 200);
    }

    public function image($fileName){
        $tes = asset('storage/customer/'.$fileName);
        return response()->json([
            'gambar' => $tes
        ], 200);
    }

    public function fotoPetshop($fileName){
        $tes = asset('storage/petshop/'.$fileName);
        return response()->json([
            'gambar' => $tes
        ], 200);
    }
}
