<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    //
    public function store(Request $request) {
        $request->validate([
            'nim'   => 'required',
            'bulan' => 'required',
            'nominal'   => 'required'
        ]);
        $isExistMahasiswa = DB::table('tbl_mahasiswa')->where('nim', $request->nim)->first();
        if($isExistMahasiswa) {
            $dataExist = DB::table('tbl_pembayaran_spp_mhs')->where('nim', $request->nim)->where('bulan_tagihan_spp', $request->bulan)->first();
            if ($dataExist) {
                return response()->json(['message' => 'Sudah terbayarkan']);
            } else {
                $data_insert = [
                    'nim' => $request->nim,
                    'bulan_tagihan_spp' => $request->bulan,
                    'nominal' => $request->nominal,
                    'status'    => 'on_success'
                ];
                DB::table('tbl_pembayaran_spp_mhs')->insert($data_insert);

                return response()->json($data_insert);
            }
        } else {
            return response()->json([
                'status'    => 'Mahasiswa Tidak Terdaftar'
            ], 500);
        }
        
    }
}
