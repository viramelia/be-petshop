<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PesananProduk;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\User;
use Validator;
use DB;

class TransaksiController extends Controller{

    public function pesan(Request $request){
        $produk = Produk::find($request->id);

        if(!$produk){
            return response()->json([
                'message' => 'produk not found'
            ], 404);
        }
        else{
            $rules = array(
                'id_petshop' => 'required',
                'id_customer' => 'required',
                'total_harga' => 'required|integer',
                'jumlah_pesanan' => 'required|integer'
            );
            $validated = Validator::make($request->all(), $rules);
            
            if($validated->fails()){
                return $validated->errors();
            }
            else{  
                $transaksi = Transaksi::where('id_customer', '=', $request->id_customer)->latest()->first()->id;
                if(!$transaksi){
                    $beforePcs = $produk->stok_produk;
                    $afterPcs = $beforePcs - $request->jumlah_pesanan;
                    $updatePcs = $produk->update(['stok_produk' => $afterPcs]);
                    $data = Transaksi::create([
                        'id_petshop' => $request->id_petshop,
                        'id_admin' => 5,
                        'id_customer' => $request->id_customer,
                        'jenis_transaksi' => 'online',
                        'total_harga' => $request->total_harga,
                        'status' => 'belum',
                    ]);

                    $pesanan = PesananProduk::create([
                        'id_petshop' => $request->id_petshop,
                        'id_produk' => $produk->id,
                        'id_transaksi' => $data->id,
                        'id_customer' => $request->id_customer,
                        'jumlah_pesanan' => $request->jumlah_pesanan,
                    ]);


                    return response()->json([
                        'message' => 'succedd to transaksi pertama',
                        'data' => $data,
                        'pesanan' => $pesanan,
                        'update_pcs' => $updatePcs,
                    ], 200);
                }
                else{
                    $checkStatus = Transaksi::where([
                        ['id', '=', $transaksi],
                        ['id_customer', '=', $request->id_customer]])->get()->pluck('bukti_tf')[0];
                    if(!$checkStatus){
                        $beforePcs = $produk->stok_produk;
                        $afterPcs = $beforePcs - $request->jumlah_pesanan;
                        $updatePcs = $produk->update(['stok_produk' => $afterPcs]);
                        $pesananBaru = PesananProduk::create([
                            'id_petshop' => $request->id_petshop,
                            'id_produk' => $produk->id,
                            'id_transaksi' => $transaksi,
                            'id_customer' => $request->id_customer,
                            'jumlah_pesanan' => $request->jumlah_pesanan,
                        ]);
                        
                        $price = Transaksi::find($transaksi)->total_harga;
                        // dd($price);
                        $price += $request->total_harga;
                        $updateHarga = Transaksi::find($transaksi)->update(['total_harga' => $price]);
                        // dd($updateHarga);
                        return response()->json([
                            'message' => 'succedd adding to chart',
                            'data'=> $pesananBaru,
                            'harga_baru' => $updateHarga
                        ], 200);
                    }
                    else{
                        $beforePcs = $produk->stok_produk;
                        $afterPcs = $beforePcs - $request->jumlah_pesanan;
                        $updatePcs = $produk->update(['stok_produk' => $afterPcs]);
                        $data = Transaksi::create([
                            'id_petshop' => $request->id_petshop,
                            'id_admin' => 5,
                            'id_customer' => $request->id_customer,
                            'jenis_transaksi' => 'online',
                            'total_harga' => $request->total_harga,
                            'status' => 'belum',
                        ]);
    
                        $pesanan = PesananProduk::create([
                            'id_petshop' => $request->id_petshop,
                            'id_produk' => $produk->id,
                            'id_transaksi' => $data->id,
                            'id_customer' => $request->id_customer,
                            'jumlah_pesanan' => $request->jumlah_pesanan,
                        ]);
    
                        return response()->json([
                            'message' => 'succedd to transaksi baru',
                            'data' => $data,
                            'pesanan' => $pesanan,
                        ], 200);
                    }
                }
            }
        }

    }

    public function pesananById($pesanan){
        $data = PesananProduk::where('id', '=', $pesanan)->get();
        $data->load('produk');
        $data->load('petshop');
        $pcs = $data[0]->jumlah_pesanan;
        $hargaProduk = $data[0]->produk->harga_satuan_produk;
        $totalPrice = $pcs * $hargaProduk;

        return response()->json([
            'message' => 'succedd get pesanan',
            'data' => $data,
            'harga' => $totalPrice,
        ], 200);
    }

    public function updateJumlah(Request $request){
        $pesananProduk = PesananProduk::find($request->id);
        $pesananProduk->load('produk');
        $harga = $pesananProduk->produk->harga_satuan_produk;
        $totalBefore = Transaksi::find($pesananProduk->id_transaksi)->total_harga;
        $produk = Produk::find($pesananProduk->id_produk);
        $beforePcs = $produk->stok_produk;
        if($pesananProduk->jumlah_pesanan > $request->jumlah_pesanan){
            $selisih = $pesananProduk->jumlah_pesanan - $request->jumlah_pesanan; 
            $hargaKurang = $selisih * $harga;
            $newPrice = $totalBefore - $hargaKurang;
            $afterPcs = $beforePcs + $selisih;
            $updateHarga = Transaksi::find($pesananProduk->id_transaksi)->update(
                ['total_harga' => $newPrice]);
            }
        else{
            $selisih = $request->jumlah_pesanan - $pesananProduk->jumlah_pesanan;
            $hargaTambah = $selisih * $harga;
            $newPrice = $totalBefore + $hargaTambah;
            $afterPcs = $beforePcs - $selisih;
            $updateHarga = Transaksi::find($pesananProduk->id_transaksi)->update(
                            ['total_harga' => $newPrice]);
        }
        
        $updatePcs = $produk->update(['stok_produk' => $afterPcs]);
        $data = $pesananProduk->update([
                    'jumlah_pesanan'=> $request->jumlah_pesanan
                ]);
        return response()->json([
            'message' => 'succedd update jumlah pesanan',
            'data' => $data,
            'harga_baru' => $updateHarga,
            'pcs_baru' => $updatePcs,
        ], 200);
    }

    public function hapusPesanan($produk){
        $pesanan = PesananProduk::find($produk);
        $pesanan->load('produk');
        $harga = Transaksi::find($pesanan->id_transaksi)->total_harga;
        $hargaPesanan = $pesanan->produk->harga_satuan_produk * $pesanan->jumlah_pesanan;
        $price = $harga - $hargaPesanan;
        // dd($price);
        $produk = Produk::find($pesanan->id_produk);
        $beforePcs = $produk->stok_produk;
        $afterPcs = $beforePcs + $pesanan->jumlah_pesanan;
        $updatePcs = $produk->update(['stok_produk' => $afterPcs]);
        $updateHarga = Transaksi::find($pesanan->id_transaksi)->update([
            'total_harga' => $price
        ]);
        $pesanan->delete();

        return response()->json([
            'message' => 'succedd delete produk from pesanan',
            'data' => $pesanan,
            'harga_baru' => $updateHarga,
            'pcs_baru' => $updatePcs,
        ], 200);
    }

    public function transaksiByCustomer($customer){
        $data = Transaksi::where([
                                    ['id_customer', '=', $customer],
                                    ['bukti_tf', '=', null]])->get();
        $data->load('pesanan');
        // $data->pluck('id');

        if($data){
            return response()->json([
                'message' => 'succedd get pesanan customer list',
                'data' => $data,
                // 'data' => $data,
            ], 200);
        }
        else{
            return response()->json([
                'message' => 'customer belum transaksi'
            ], 404);
        }
    }

    public function checkout($customer, $idTranskasi){
        $transaksi = Transaksi::find($idTranskasi);
        $petshop = User::find($transaksi->id_petshop);
        if($transaksi){
            // $data = Transaksi::where('id_customer', '=', $customer)->get();
            // $data->load('pesanan');
            $harga = array();
            $total = $transaksi->total_harga;
            
            // foreach($data->pluck('pesanan')[0] as $data){
                // dd($data->id_produk);
            //     $idProduk = $data->id_produk;
            //     $hargaProduk = Produk::find($idProduk)->harga_satuan_produk; 
            //     $total = $hargaProduk * $data->jumlah_pesanan;
            //     array_push($harga, $total);
            // }
    
            // foreach($harga as $data){
            //     $totalHarga+= $data;
            // }
    
            // $updateTotal = $transaksi->update(['total_harga'=> $totalHarga]); 
    
            return response()->json([
                'message' => 'ready to checkout',
                'petshop' => $petshop,
                'data' => $total,
            ], 200);
        }
    }

    public function uploadBukti(Request $request, $transaksi){
        $data = Transaksi::find($transaksi);
        $rules = array(
            'bukti_tf'=> 'required|mimes:jpg,png',
        );

        $validated = Validator::make($request->all(), $rules);

        if($validated->fails()){    
            return $validated->errors();
        }
        else{
            $namaFoto = $request->nama_lengkap.time().'.'.$request->file('bukti_tf')->extension();
            $foto = $request->file('bukti_tf')
                    ->storeAs('public/bukti-tf', $namaFoto);
            $data->update(['bukti_tf' => $namaFoto]);

            return response()->json([
                'message' => 'succedd upload bukti',
                'data' => $data,
            ], 201);
        }
    }

    public function transaksiById(Request $request){
        $data = Transaksi::where([
                    ['id_customer', '=', $request->id],
                    ['bukti_tf', '!=', null]])->get();
        $data->load('pesanan');
        $data->load('petshop');
        // dd($data);
        // dd($data[0]->pesanan->pluck('id_produk'));
        foreach($data as $a){
            $a->bukti_tf = asset('storage/bukti-tf/'.$a->bukti_tf);
        }

        // dd($data->pluck('id_customer'));
        return response()->json([
            'message' => 'succedd get data transaksi',
            'data' => $data,
            // 'gambat' => ''
        ], 200);
    }

    public function toConfirmAdmin(){
        $data = Transaksi::where([['bukti_tf', '!=', null],
                ['status', '=', 'belum']])->get();
        $data->load('petshop');
        $data->load('customer');

        return response()->json([
            'message' => 'all transaksi to confirm',
            'data' => $data
        ], 200);
    }

    public function transaksiDetail($transaksi){
        $data = Transaksi::find($transaksi);
        $data->load('customer');
        $data->load('petshop');

        $pesanan = PesananProduk::where('id_transaksi', '=', $transaksi)->get();
        $pesanan->load('produk');
        $data->bukti_tf = asset('storage/bukti-tf/'.$data->bukti_tf);
        return response()->json([
            'message' => 'detail transaksi',
            'data' => $data,
            'pesanan' => $pesanan,
        ], 200);
    }

    public function updateStatus(Request $request){
        $data = Transaksi::find($request->id);
        $data->update(['status' => $request->status]);

        return response()->json([
            'message' => 'transaksi updated',
            'data' => $data
        ], 200);
    }

    public function transaksiConfirmedPetshop($petshop, $status){
        $statusLength = strlen($status);
        if($statusLength == 25){
            $newStatus = explode('&', $status); 
            $data = Transaksi::where([['id_petshop', '=', $petshop],
                                    ['status', '=', $newStatus[0]]])->
                                orWhere([['id_petshop', '=', $petshop],
                                ['status', '=', $newStatus[1]]])->
                                orWhere([['id_petshop', '=', $petshop],
                                ['status', '=', $newStatus[2]]])->get();
            $data->load('customer');
            $data->load('pesanan'); 

            foreach($data as $a){
                $a->bukti_tf = asset('storage/bukti-tf/'. $a->bukti_tf);
            }
        }
        else{
            $data = Transaksi::where([['id_petshop', '=', $petshop],
                                    ['status', '=', $status]])->get();
            $data->load('customer');
            $data->load('pesanan'); 
        }
        
        // dd($pesanan);

        return response()->json([
            'message' => 'all confirmed transaksi by admin',
            'data' => $data,
        ], 200);
    }

    public function setStatusTransaksi(Request $request){
        $data = Transaksi::find($request->id);
        $data->update(['status' => $request->status]);

        return response()->json([
            'messsage' => 'update transaksi status berhasil',
            'data' => $data
        ], 200);
    }

    public function pesananTransaksi($idTransaksi){
        $pesanan = PesananProduk::where('id_transaksi', '=', $idTransaksi)->get();
        // $pesanan->load('produk');

        return response()->json([
            'message' => 'list pesanan by transaksi',
            'data' => $pesanan 
        ], 200);
    }

   public function checkoutTransaksiOffline(Request $request, $petshop){
       $pesanan = $request->pesanan;
    //    dd($request->total_harga);
       $transaksi = Transaksi::create([
            'id_petshop' => $petshop,
            'id_customer' => 37,
            'id_admin' => 5,
            'jenis_transaksi' => 'offline',
            'status' => 'diterima',
            'total_harga' => $request->total_harga
       ]);

        foreach($pesanan as $data){
            $produk = Produk::find($data[0]);
            $produk->update([
                'stok_produk' => $produk->stok_produk - $data[1]
            ]);

            $pesananProduk = PesananProduk::create([
                'id_petshop' => $petshop,
                'id_produk' => $data[0],
                'id_transaksi' => $transaksi->id,
                'id_customer' => 37,
                'jumlah_pesanan' => $data[1],
            ]);
       }

       return response()->json([
           'message' => 'Transaksi offline done',
           'data' => $transaksi,
       ], 201);
   }

   public function image($fileName){
    $tes = asset('storage/bukti-tf/'.$fileName);
    return response()->json([
        'gambar' => $tes
    ], 200);
}
}
