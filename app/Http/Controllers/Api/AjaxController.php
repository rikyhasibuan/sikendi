<?php

namespace App\Http\Controllers\Api;

use App\Models\Kegiatan;
use App\Models\Belanja;
use App\Libraries\Common;
use App\Libraries\KasAnggaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * AjaxController class
 */
class AjaxController extends Controller
{
    /**
     * menampilkan data kegiatan berdasarkan program tertentu
     * @param Request $request
     * @return JsonResponse
     */
    public function show_kegiatan_by_program(Request $request)
    {
        return response()->json(Kegiatan::where('program_id', $request['program'])->get(), 200);
    }

    /**
     * menampilkan data belanja berdasarkan kegiatan tertentu
     * @param Request $request
     * @return JsonResponse
     */
    public function show_belanja_by_kegiatan(Request $request)
    {
        return response()->json(Belanja::where('kegiatan_id', $request['kegiatan'])->get(), 200);
    }

    /**
     * menampilkan total anggaran yang tersedia pada tahun dan kode belanja tertentu
     * @param Request $request
     * @return JsonResponse
     */
    public function show_total_anggaran(Request $request)
    {
        $anggaran = new KasAnggaran();
        $common = new Common();
        $tahun = $common->generate_year_from_date($request->input('tahun'));
        $belanja = $request->input('belanja');
        $totalanggaran = $anggaran->show_total_anggaran($tahun, $belanja);
        return response()->json(['total_anggaran'=>$totalanggaran], 200);
    }

    /**
     * menampilkan sisa anggaran pada tahun dan kode belanja tertentu
     * @param Request $request
     * @return JsonResponse
     */
    public function show_sisa_anggaran(Request $request)
    {
        $common = new Common();
        $tahun = $common->generate_year_from_date($request->input('tahun'));
        $kegiatan = $request->input('kegiatan');

        $kas = new KasAnggaran();
        $sisa_anggaran = $kas->show_sisa_anggaran($tahun, '', $kegiatan);

        return response()->json(['sisa_anggaran' => $sisa_anggaran], 200);
    }

    /**
     * ambil data golongan berdasarkan pangkat
     * @param Request $request
     * @return JsonResponse
     */
    public function show_golongan_by_pangkat(Request $request)
    {
        $pangkat = Pangkat::where('nama_pangkat', $request->input('pangkat'))->first();
        return response()->json(['golongan' => $pangkat['golongan']], 200);
    }
}
