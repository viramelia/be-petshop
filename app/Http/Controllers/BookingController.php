<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Layanan;
use App\Models\BookingLayanan;

class BookingController extends Controller
{
    public function pesan(Request $request){
        // dd($request->id);
        $layanan = Layanan::find($request->id);

        if($layanan ){
            $jenisLayanan = BookingLayanan::where('id_layanan', $request->id)->first();
            $tanggal = BookingLayanan::where('tgl_booking', $request->tgl_booking)->first();
            $jam = BookingLayanan::where('jam_mulai', $request->jam_mulai)->first();
            // dd($jam->jam_mulai);
            if($jenisLayanan && $tanggal && $jam){
                return response()->json([
                    'message' => 'Oops...already booked'
                ], 400);
            }
            else{
                $rules = array(
                    'id_petshop' => 'required',
                    'id_customer' => 'required',
                    'tgl_booking' => 'required|date_format:Y-m-d',
                    'jam_mulai' => 'required|date_format:H:i',
                    // 'jam_selesai' => 'required|date_format:H:i',
                    'jenis_hewan' => 'required|min: 4',
                );
                $validated = Validator::make($request->all(), $rules);
        
                if($validated->fails()){
                    return $validated->errors();
                }
                else{
                    $jam_selesai = strtotime($request->jam_mulai) + 60 * 60;
                    $selesai = date('H:i', $jam_selesai);
                    $data = BookingLayanan::create([
                        'id_petshop'=> $request->id_petshop,
                        'id_customer'=> $request->id_customer,
                        'id_layanan'=> $request->id,
                        'tgl_booking'=> $request->tgl_booking,
                        'jam_mulai'=> $request->jam_mulai,
                        'jam_selesai'=> $selesai,
                        'jenis_transaksi' => 'online',
                        'jenis_hewan' => $request->jenis_hewan,
                        'status'=> 'terbooking'
                    ]);
        
                    return response()->json([
                        'message' => 'Succedd to booking layanan',
                        'data' => $data,
                    ], 201);
                }
            }
        }
        else{
            return response()->json([
                'message' => 'Layanan not found',
            ], 404);
        }
    }

    public function layananByCustomer($customer, $status){
        $data = BookingLayanan::where([['id_customer', '=', $customer],
                                        ['status', '=', $status]])->paginate(5);
        $data->load('layanan');
        $data->load('petshop');
        
        return response()->json([
            'message' => 'layanan terbooking customer',
            'data' => $data
        ], 200);
    }

    public function layananBookedByPetshop($petshop, $status){
        // dd($status);
        $data = BookingLayanan::where([['id_petshop', '=', $petshop],
                                ['status', '=', $status]])->get();
        $data->load('customer');
        $data->load('layanan');
        return response()->json([
            'message' => 'layanan terbooking by petshop',
            'data' => $data
        ], 200);
    }

    public function bookingById($booking){
        // dd($booking);
        $data = BookingLayanan::find($booking);
        $data->load('petshop');
        $data->load('customer');
        $data->load('layanan');

        return response()->json([
            'message' => 'succedd get data booking',
            'data' => $data
        ], 200);
    }

    public function setStatusBooking(Request $request){
        $rules = array(
            'biaya' => 'required',
            'berat_hewan' => 'required',
            'status' => 'required',
        );

        $validated = Validator::make($request->all(), $rules);
    
        if($validated->fails()){
            return $validated->errors();
        }
        else{
            $data = BookingLayanan::find($request->id);
            $newData = array(
                'biaya' => $request->biaya,
                'status' => $request->status,
                'berat_hewan' => (int)$request->berat_hewan,
            );
            $data->fill($newData);
            $data->push();
    
            return response()->json([
                'message' => 'Status booking berhasil di update',
                'data' => $data,
            ], 200);
        }
    }

    public function deleteBooking($idBooking){
        $data = BookingLayanan::find($idBooking);
        $data->delete();
        
        return response()->json([
            'message' => 'booking layanan success deleted'
        ], 200);
        
    }

    public function kategoriLayananByPetshop($petshop){
        $kategori = Layanan::distinct()->where([['id_petshop', '=', $petshop]])->get(['kategori']);

        return response()->json([
            'message' => 'all kategori by petshop',
            'data' => $kategori
        ], 200);
    }

    public function layananPetshopByKategori($petshop, $kategori){
        // dd($kategori);
        $layanan = Layanan::where([['id_petshop', '=', $petshop],
                                    ['kategori', '=', $kategori ]])->get();

        return response()->json([
            'message' => 'layanan by kategori',
            'data' => $layanan
        ], 200);
    }

    public function bookingOffline(Request $request, $petshop){
        $layanan = Layanan::find($request->id_layanan);

        if($layanan ){
            $jenisLayanan = BookingLayanan::where('id_layanan', $request->id_layanan)->first();
            $tanggal = BookingLayanan::where('tgl_booking', $request->tgl_booking)->first();
            $jam = BookingLayanan::where('jam_mulai', $request->jam_mulai)->first();
            // dd($jam->jam_mulai);
            if($jenisLayanan && $tanggal && $jam){
                return response()->json([
                    'message' => 'Oops...already booked'
                ], 400);
            }
            else{
                $rules = array(
                    'tgl_booking' => 'required|date_format:Y-m-d',
                    'jam_mulai' => 'required|date_format:H:i',
                    'jenis_hewan' => 'required|min: 4',
                    'berat_hewan' => 'required|integer'
                );
                $validated = Validator::make($request->all(), $rules);
        
                if($validated->fails()){
                    return $validated->errors();
                }
                else{
                    $jam_selesai = strtotime($request->jam_mulai) + 60 * 60;
                    $selesai = date('H:i', $jam_selesai);
                    $data = BookingLayanan::create([
                        'id_petshop'=> $petshop,
                        'id_customer'=> 37,
                        'id_layanan'=> $request->id_layanan,
                        'tgl_booking'=> $request->tgl_booking,
                        'jam_mulai'=> $request->jam_mulai,
                        'jam_selesai'=> $selesai,
                        'jenis_transaksi' => 'offline',
                        'jenis_hewan' => $request->jenis_hewan,
                        'status'=> 'selesai'
                    ]);
        
                    return response()->json([
                        'message' => 'Succedd to booking layanan',
                        'data' => $data,
                    ], 201);
                }
            }
        }
        else{
            return response()->json([
                'message' => 'Layanan not found',
            ], 404);
        }
    }
}

