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

            $anggaran = Anggaran::where('tahun', $tahun)->sum('jumlah');
            $sp2d = Sp2d::whereYear('tgl_sp2d', $tahun)->sum('jumlah_sp2d');
            $pelimpahan = Pelimpahan::whereYear('tgl_nota_dinas', $tahun)->sum('jumlah_pelimpahan');
            $sp2t = Sp2t::whereYear('tgl_nota_dinas', $tahun)->sum('jumlah_transfer');

            // SP2D
            $sp2d_jan = Sp2d::whereMonth('tgl_sp2d','=','01')->whereYear('created_at', '=', $tahun)->sum('jumlah_sp2d');
            $sp2d_feb = Sp2d::whereMonth('tgl_sp2d','=','02')->whereYear('created_at', '=', $tahun)->sum('jumlah_sp2d');
            $sp2d_mar = Sp2d::whereMonth('tgl_sp2d','=','03')->whereYear('created_at', '=', $tahun)->sum('jumlah_sp2d');
            $sp2d_apr = Sp2d::whereMonth('tgl_sp2d','=','04')->whereYear('created_at', '=', $tahun)->sum('jumlah_sp2d');
            $sp2d_mei = Sp2d::whereMonth('tgl_sp2d','=','05')->whereYear('created_at', '=', $tahun)->sum('jumlah_sp2d');
            $sp2d_jun = Sp2d::whereMonth('tgl_sp2d','=','06')->whereYear('created_at', '=', $tahun)->sum('jumlah_sp2d');
            $sp2d_jul = Sp2d::whereMonth('tgl_sp2d','=','07')->whereYear('created_at', '=', $tahun)->sum('jumlah_sp2d');
            $sp2d_agu = Sp2d::whereMonth('tgl_sp2d','=','08')->whereYear('created_at', '=', $tahun)->sum('jumlah_sp2d');
            $sp2d_sep = Sp2d::whereMonth('tgl_sp2d','=','09')->whereYear('created_at', '=', $tahun)->sum('jumlah_sp2d');
            $sp2d_okt = Sp2d::whereMonth('tgl_sp2d','=','10')->whereYear('created_at', '=', $tahun)->sum('jumlah_sp2d');
            $sp2d_nov = Sp2d::whereMonth('tgl_sp2d','=','11')->whereYear('created_at', '=', $tahun)->sum('jumlah_sp2d');
            $sp2d_des = Sp2d::whereMonth('tgl_sp2d','=','12')->whereYear('created_at', '=', $tahun)->sum('jumlah_sp2d');

            // Pelimpahan
            $pelimpahan_jan = Pelimpahan::whereMonth('tgl_nota_dinas','=','01')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_pelimpahan');
            $pelimpahan_feb = Pelimpahan::whereMonth('tgl_nota_dinas','=','02')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_pelimpahan');
            $pelimpahan_mar = Pelimpahan::whereMonth('tgl_nota_dinas','=','03')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_pelimpahan');
            $pelimpahan_apr = Pelimpahan::whereMonth('tgl_nota_dinas','=','04')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_pelimpahan');
            $pelimpahan_mei = Pelimpahan::whereMonth('tgl_nota_dinas','=','05')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_pelimpahan');
            $pelimpahan_jun = Pelimpahan::whereMonth('tgl_nota_dinas','=','06')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_pelimpahan');
            $pelimpahan_jul = Pelimpahan::whereMonth('tgl_nota_dinas','=','07')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_pelimpahan');
            $pelimpahan_agu = Pelimpahan::whereMonth('tgl_nota_dinas','=','08')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_pelimpahan');
            $pelimpahan_sep = Pelimpahan::whereMonth('tgl_nota_dinas','=','09')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_pelimpahan');
            $pelimpahan_okt = Pelimpahan::whereMonth('tgl_nota_dinas','=','10')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_pelimpahan');
            $pelimpahan_nov = Pelimpahan::whereMonth('tgl_nota_dinas','=','11')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_pelimpahan');
            $pelimpahan_des = Pelimpahan::whereMonth('tgl_nota_dinas','=','12')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_pelimpahan');

            // SP2T
            $sp2t_jan = Sp2t::whereMonth('tgl_nota_dinas','=','01')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_transfer');
            $sp2t_feb = Sp2t::whereMonth('tgl_nota_dinas','=','02')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_transfer');
            $sp2t_mar = Sp2t::whereMonth('tgl_nota_dinas','=','03')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_transfer');
            $sp2t_apr = Sp2t::whereMonth('tgl_nota_dinas','=','04')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_transfer');
            $sp2t_mei = Sp2t::whereMonth('tgl_nota_dinas','=','05')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_transfer');
            $sp2t_jun = Sp2t::whereMonth('tgl_nota_dinas','=','06')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_transfer');
            $sp2t_jul = Sp2t::whereMonth('tgl_nota_dinas','=','07')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_transfer');
            $sp2t_agu = Sp2t::whereMonth('tgl_nota_dinas','=','08')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_transfer');
            $sp2t_sep = Sp2t::whereMonth('tgl_nota_dinas','=','09')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_transfer');
            $sp2t_okt = Sp2t::whereMonth('tgl_nota_dinas','=','10')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_transfer');
            $sp2t_nov = Sp2t::whereMonth('tgl_nota_dinas','=','11')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_transfer');
            $sp2t_des = Sp2t::whereMonth('tgl_nota_dinas','=','12')->whereYear('tgl_nota_dinas', '=', $tahun)->sum('jumlah_transfer');

            $sp2d_chart = [
                intval($sp2d_jan),
                intval($sp2d_feb),
                intval($sp2d_mar),
                intval($sp2d_apr),
                intval($sp2d_mei),
                intval($sp2d_jun),
                intval($sp2d_jul),
                intval($sp2d_agu),
                intval($sp2d_sep),
                intval($sp2d_okt),
                intval($sp2d_nov),
                intval($sp2d_des)
            ];

            $pelimpahan_chart = [
                intval($pelimpahan_jan),
                intval($pelimpahan_feb),
                intval($pelimpahan_mar),
                intval($pelimpahan_apr),
                intval($pelimpahan_mei),
                intval($pelimpahan_jun),
                intval($pelimpahan_jul),
                intval($pelimpahan_agu),
                intval($pelimpahan_sep),
                intval($pelimpahan_okt),
                intval($pelimpahan_nov),
                intval($pelimpahan_des)
            ];

            $sp2t_chart = [
                intval($sp2t_jan),
                intval($sp2t_feb),
                intval($sp2t_mar),
                intval($sp2t_apr),
                intval($sp2t_mei),
                intval($sp2t_jun),
                intval($sp2t_jul),
                intval($sp2t_agu),
                intval($sp2t_sep),
                intval($sp2t_okt),
                intval($sp2t_nov),
                intval($sp2t_des)
            ];

            return response()->json([
                'anggaran' => $anggaran,
                'sp2d' => $sp2d,
                'pelimpahan' => $pelimpahan,
                'sp2t' => $sp2t,
                'sp2d_chart' => $sp2d_chart,
                'pelimpahan_chart' => $pelimpahan_chart,
                'sp2t_chart' => $sp2t_chart,
            ], 200);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
