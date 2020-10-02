<?php

namespace App\Http\Controllers\Api;

use App\Models\Pangkat;
use App\Models\Anggaran;
use App\Models\Kegiatan;
use App\Models\Belanja;
use App\Models\PelimpahanDetail;
use App\Models\Penerima;
use App\Models\Pegawai;
use App\Libraries\Common;
use App\Libraries\KasAnggaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AjaxController extends Controller
{
    public function show_anggaran_by_bendahara(Request $request)
    {
        $kegiatan = Kegiatan::where('bendahara', $request['bendahara'])->get();
        $total_anggaran = 0;
        $kegiatan_list = [];
        
        foreach ($kegiatan as $v) {
            $kegiatan_list[] = $v->id;
        }

        $pelimpahan = PelimpahanDetail::where('bendahara', $request['bendahara'])->sum('jumlah_pelimpahan');
        $anggaran = Anggaran::whereIn('kegiatan_id', $kegiatan_list)->sum('jumlah');
        $total_anggaran = $anggaran - $pelimpahan;
        return $total_anggaran;
    }

    public function show_anggaran_by_belanja_bendahara(Request $request)
    {
        $bendahara  = Pegawai::where('nip', $request['bendahara'])->first();
        $pelimpahan = PelimpahanDetail::where('bendahara', $bendahara['id'])->sum('jumlah_pelimpahan');
        $anggaran   = Anggaran::where('belanja_id', $request['belanja'])->sum('jumlah');
        $total_anggaran = $anggaran - $pelimpahan;
        return $total_anggaran;
    }

    public function show_nama_penerima_sp2t(Request $request)
    {
        $penerima = Penerima::where('nama_penerima', 'LIKE', '%'.$request['query'].'%')->get();
        $response = [];
        foreach ($penerima as $v) {
            array_push($response, ['nama_penerima' => $v->nama_penerima, 'norek_penerima' => $v->norek]);
        }
        return response()->json($response, 200);
    }

    public function show_kegiatan_by_program(Request $request)
    {
        if (isset($request['bendahara'])) {
            $pegawai = Pegawai::where('nip', $request['bendahara'])->first();
            $kegiatan = Kegiatan::where('program_id', $request['program'])->where('bendahara', $pegawai['id'])->get();
        } else {
            $kegiatan = Kegiatan::where('program_id', $request['program'])->get();
        }
        return response()->json($kegiatan, 200);
    }

    public function show_belanja_by_kegiatan(Request $request)
    {
        return response()->json(Belanja::where('kegiatan_id', $request['kegiatan'])->get(), 200);
    }

    public function show_total_anggaran(Request $request)
    {
        $anggaran = new KasAnggaran();
        $common = new Common();
        $tahun = $common->generate_year_from_date($request->input('tahun'));
        $belanja = $request->input('belanja');
        $totalanggaran = $anggaran->show_total_anggaran($tahun, $belanja);
        return response()->json(['total_anggaran'=>$totalanggaran], 200);
    }

    public function show_sisa_anggaran(Request $request)
    {
        $common = new Common();
        $tahun = $common->generate_year_from_date($request->input('tahun'));
        $kegiatan = $request->input('kegiatan');

        $kas = new KasAnggaran();
        $sisa_anggaran = $kas->show_sisa_anggaran($tahun, '', $kegiatan);

        return response()->json(['sisa_anggaran' => $sisa_anggaran], 200);
    }

    public function show_golongan_by_pangkat(Request $request)
    {
        $pangkat = Pangkat::where('nama_pangkat', $request->input('pangkat'))->first();
        return response()->json(['golongan' => $pangkat['golongan']], 200);
    }
}
