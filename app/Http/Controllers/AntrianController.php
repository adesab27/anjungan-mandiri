<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AntrianController extends Controller
{
    public function ambil(Request $request)
    {
        $kode = $request->kode;

        // mapping loket
        $loketMap = [
            'A' => 1,
            'B' => 2,
            'C' => 3,
            'D' => 4
        ];

        $loket = $loketMap[$kode];

        // ambil nomor terakhir
        $last = DB::table('antrian')
            ->where('loket', $loket)
            ->count();

        $nomor = $kode . '-' . str_pad($last + 1, 3, '0', STR_PAD_LEFT);

        // simpan
        DB::table('antrian')->insert([
            'nomor' => $nomor,
            'loket' => $loket,
            'status' => 'menunggu',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'nomor' => $nomor
        ]);
    }

    public function terakhir()
    {
        $data = DB::table('antrian')
            ->where('status', 'dipanggil')
            ->latest()
            ->first();

        return response()->json([
            'nomor' => $data ? $data->nomor : '-'
        ]);
    }
}