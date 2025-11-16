<?php

namespace App\Http\Controllers\Voice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class WilayahController extends Controller
{
    public function getKabupaten($provId)
    {
        $data = DB::table('kabupaten')
            ->where('kdprovinsi', $provId)
            ->select('kdkabupaten', 'kabupaten')
            ->get();
        return response()->json($data);
    }

    public function getKecamatan($kabId)
    {
        $data = DB::table('kecamatan')
            ->where('kdkabupaten', $kabId)
            ->select('kdkecamatan', 'kecamatan')
            ->get();
        return response()->json($data);
    }

    public function getDesa($kecId)
    {
        $data = DB::table('desa')
            ->where('kdkecamatan', $kecId)
            ->select('kddesa', 'desa')
            ->get();
        return response()->json($data);
    }
}
