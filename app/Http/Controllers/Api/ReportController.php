<?php


namespace App\Http\Controllers\Api;

use App\Exports\AnggaranExport;
use App\Models\Anggaran;
use App\Models\Kegiatan;
use App\Models\Belanja;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\View;

class ReportController extends Controller
{
    public function print_data(Request $request) {
        try {
            $_dari = isset($request['dari']) ? $request['dari'] : '';
            $_sampai = isset($request['sampai']) ? $request['sampai'] : '';
            $_bendahara = isset($request['bendahara']) ? $request['bendahara'] : '';
            $_jenis = isset($request['jenis']) ? $request['jenis'] : '';
            $count = Kegiatan::searchBendahara($_bendahara)->count();
            $view = '';
            if ($count > 0) {
                if ($_jenis == 'excel') {
                    return Excel::download(new AnggaranExport($_dari), 'Laporan Data Kendali Keuangan Bendahara Pengeluaran (BP).xlsx');
                } elseif ($_jenis == 'pdf') {
                    $dari = explode('-', $_dari);
                    return View::make('pdf', ['dari' => $dari[1], 'tahun' => $dari[0]]);
                };
            } else {
                return View::make('404');
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
