<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
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
            'expires_in' => auth()->factory()->getTTL() * 60,
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
            return $validated->errors();
        }
        else{
            $namaFoto = $request->nama_lengkap.time().'.'.$request->file('foto')->extension();
            $foto = $request->file('foto')
                    ->storeAs('petshop', $namaFoto);
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
                    ->storeAs('customer', $namaFoto);
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
}
