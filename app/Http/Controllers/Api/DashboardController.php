<?php

namespace App\Http\Controllers\Api;

use App\Models\Anggaran;
use App\Models\Sp2d;
use App\Models\Pelimpahan;
use App\Models\Sp2t;
use App\Models\Kegiatan;
use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\Common;

class DashboardController extends Controller
{
    public function getData(Request $request)
    {
        try {
            $common = new Common();
            $tahun = ($request['tahun'] != '') ? $request['tahun'] : date('Y');
            $bulan = ($request['bulan'] != '') ? $request['bulan'] : '12';

            $anggaran = Anggaran::where('tahun', date('Y'))->first();
            $sp2d = Sp2d::whereYear('tgl_sp2d', date('Y'))->sum('jumlah_sp2d');
            $pelimpahan = Pelimpahan::whereYear('tgl_nota_dinas', date('Y'))->sum('jumlah_pelimpahan');
            $sp2t = Sp2t::whereYear('tgl_nota_dinas', date('Y'))->sum('jumlah_transfer');
            
            return response()->json(['anggaran' => $anggaran['jumlah'], 'sp2d' => $sp2d, 'pelimpahan' => $pelimpahan, 'sp2t' => $sp2t], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
