<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Libraries\Common;
use App\Models\Sp2t;
use App\Models\Sp2tDetail;
use App\Models\Sp2tRevisi;
use App\Models\Pelimpahan;
use App\Models\PelimpahanDetail;
use App\Models\Pegawai;
use App\Models\Penerima;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Exception;

class Sp2tController extends Controller
{
    protected $_common;

    public function __construct()
    {
        $this->_common = new Common();
    }

    public function get_data(Request $request)
    {
        try {
            $_q = ($request['q'] !== '') ? $request['q'] : '';
            $_start = ($request['start'] !== '') ? $request['start'] : '';
            $_end = ($request['end'] !== '') ? $request['end'] : '';
            $sp2t = Sp2t::searchNotaDinas($_q)
            ->searchAwalPeriode($_start)
            ->searchAkhirPeriode($_end)
            ->orderBy('id', 'DESC')
            ->with('pegawai')
            ->orderBy('id', 'DESC')
            ->paginate(10);

            return response()->json($sp2t, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show_data(Request $request)
    {
        return response()->json(Pelimpahan::find($request['id']), 200);
    }

    public function delete_data(Request $request)
    {
        $detail = Sp2tDetail::find($request['id']);
        $old_jml = $detail->nominalbruto;
        $parent_id = $detail->sp2t_id;
        if ($detail->delete()) {
            $sp2t = Sp2t::find($parent_id);
            $jml = $sp2t->jumlah_transfer;
            $sisa = $sp2t->sisa_pelimpahan;
            $anggaran = $sp2t->sisa_anggaran;
            $sp2t->jumlah_transfer = $jml - $old_jml;
            $sp2t->sisa_pelimpahan = $sisa + $old_jml;
            $sp2t->sisa_anggaran = $anggaran + $old_jml;
            $sp2t->save();
            $payload = [
                'page' => 'SP2T',
                'message' => 'User dengan NIP '.$request->query('nip').' melakukan hapus data pada SP2T'
            ];
            $this->_common->generate_log($payload);
            return response()->json(['status' => 'ok'], 200);
        } else {
            return response()->json(['status' => 'failed'], 500);
        }
    }

    public function post_data(Request $request)
    {
        $sp2t = Sp2t::find($request['sp2t']);
        $detail = new Sp2tDetail();

        $tf = 0;
        $tf = $request->input('nominalbruto') - ($request->input('ppn') + $request->input('pph22') + $request->input('pph4') + $request->input('pph21') + $request->input('pph23'));

        $detail->sp2t_id = $request['sp2t'];
        $detail->program_id = $request->input('program_id');
        $detail->kegiatan_id = $request->input('kegiatan_id');
        $detail->belanja_id = $request->input('belanja_id');
        $detail->nama_penerima_sp2t = $request->input('nama_penerima_sp2t');
        $detail->nomor_penerima_sp2t = $request->input('nomor_penerima_sp2t');
        $detail->nominalbruto = $request->input('nominalbruto');
        $detail->ppn = $request->input('ppn');
        $detail->pph22 = $request->input('pph22');
        $detail->pph4 = $request->input('pph4');
        $detail->pph21 = $request->input('pph21');
        $detail->pph23 = $request->input('pph23');
        $detail->nominal_transfer = $tf;
        $detail->keterangan = $request->input('keterangan');
        $detail->created_at = date('Y-m-d H:i:s');
        if ($detail->save()) {
            $jml = $sp2t->jumlah_transfer;
            $sisa = $sp2t->sisa_pelimpahan;
            $anggaran = $sp2t->sisa_anggaran;
            $sp2t->jumlah_transfer = $jml + $detail->nominalbruto;
            $sp2t->sisa_anggaran = $anggaran - $detail->nominalbruto;
            $sp2t->sisa_pelimpahan = $sisa - ($jml + $detail->nominalbruto);
            $sp2t->save();

            $payload = [
                'page' => 'SP2T',
                'message' => 'User dengan NIP '.$request->query('nip').' menambahkan data SP2T baru'
            ];
            $this->_common->generate_log($payload);

            $penerima = new Penerima();
            $penerima->nama_penerima = $request->input('nama_penerima_sp2t');
            $penerima->norek = $request->input('nomor_penerima_sp2t');
            $penerima->save();
            return response()->json(['status'=>'ok'], 200);
        } else {
            return response()->json(['status'=>'failed'], 500);
        }
    }

    public function put_data(Request $request)
    {
        $sp2t = Sp2t::find($request['sp2t']);
        $detail = Sp2tDetail::find($request['id']);
        $old_jml = $detail->nominalbruto;

        $tf = 0;
        if ($request->input('ppn') != 0) {
            $tf = $request->input('nominalbruto') - $request->input('ppn');
        } elseif ($request->input('pph22') != 0) {
            $tf = $request->input('nominalbruto') - $request->input('pph22');
        } elseif ($request->input('pph4') != 0) {
            $tf = $request->input('nominalbruto') - $request->input('pph4');
        } elseif ($request->input('pph21') != 0) {
            $tf = $request->input('nominalbruto') - $request->input('pph21');
        } elseif ($request->input('pph23') != 0) {
            $tf = $request->input('nominalbruto') - $request->input('pph23');
        } else {
            $tf = $request->input('nominalbruto');
        }

        $detail->sp2t_id = $request['sp2t'];
        $detail->program_id = $request->input('program_id');
        $detail->kegiatan_id = $request->input('kegiatan_id');
        $detail->belanja_id = $request->input('belanja_id');
        $detail->nama_penerima_sp2t = $request->input('nama_penerima_sp2t');
        $detail->nomor_penerima_sp2t = $request->input('nomor_penerima_sp2t');
        $detail->nominalbruto = $request->input('nominalbruto');
        $detail->ppn = $request->input('ppn');
        $detail->pph22 = $request->input('pph22');
        $detail->pph4 = $request->input('pph4');
        $detail->pph21 = $request->input('pph21');
        $detail->pph23 = $request->input('pph23');
        $detail->nominal_transfer = $tf;
        $detail->keterangan = $request->input('keterangan');
        $detail->created_at = date('Y-m-d H:i:s');
        if ($detail->save()) {
            $jml = $sp2t->jumlah_transfer;
            $sisa = $sp2t->sisa_pelimpahan;
            $sp2t->jumlah_transfer = $jml - $old_jml + $detail->nominalbruto;
            $sp2t->sisa_pelimpahan = $sisa + $old_jml - ($jml + $detail->nominalbruto);
            $sp2t->save();
            $payload = [
                'page' => 'SP2T',
                'message' => 'User dengan NIP '.$request->query('nip').' melakukan ubah data pada SP2T'
            ];
            $this->_common->generate_log($payload);

            Penerima::updateOrCreate(
                ['nama_penerima_sp2t' => $request->input('nama_penerima_sp2t'), 'nomor_penerima_sp2t' => $request->input('nama_penerima_sp2t')],
                ['nama_penerima_sp2t' => $request->input('nama_penerima_sp2t'), 'nomor_penerima_sp2t' => $request->input('nama_penerima_sp2t')]
            );
            return response()->json(['status'=>'ok'], 200);
        } else {
            return response()->json(['status'=>'failed'], 500);
        }
    }

    public function delete_nominal_data(Request $request)
    {
        $sp2t = Sp2tDetail::find($request['id']);
        $bruto = $sp2t->nominalbruto;
        $net = $sp2t->nominal_transfer;
        $sp2t_id = $sp2t->nominalbruto;
        if ($pelimpahan->delete()) {
            $parent = Sp2t::find($sp2t_id);
            $parent->jumlah_transfer = $parent->jumlah_transfer - $bruto;
            $parent->save();
            $payload = [
                'page' => 'SP2T',
                'message' => 'User dengan NIP '.$request->query('nip').' melakukan hapus data pada SP2T'
            ];
            $this->_common->generate_log($payload);
            return response()->json(['status' => 'ok'], 200);
        } else {
            return response()->json(['status' => 'failed'], 500);
        }
    }

    public function get_print(Request $request)
    {
        try {
            $_id = isset($request['id']) ? $request['id'] : '';
            $sp2t = Sp2t::with('pegawai')->find($_id);
            $detail = Sp2tDetail::with('program', 'kegiatan', 'belanja')->where('sp2t_id', $_id)->get();
            $view = View::make('sp2t.print', ['sp2t' => $sp2t, 'detail' => $detail]);
            return $view;
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function post_approval_data(Request $request)
    {
        $id   = isset($request['id']) ? $request['id'] : '';
        $act  = isset($request['act']) ? $request['act'] : '';
        $role = isset($request['role']) ? $request['role'] : '';
        $nip  = isset($request['nip']) ? $request['nip'] : '';

        if ($act == 'revision') {
            $revision = new Sp2tRevisi();
            $revision->nip = $nip;
            $revision->role = $role;
            $revision->sp2t_id = $id;
            $revision->catatan = $request->input('catatan');
            $revision->created_at = date('Y-m-d H:i:s');
            if ($revision->save()) {
                return response()->json(['status'=>'ok'], 200);
            } else {
                return response()->json(['status'=>'failed'], 500);
            }
        } elseif ($act == 'approve') {
            $sp2t = Sp2t::find($id);
            if ($role == 'verifikatur') {
                $sp2t->approval_verifikatur = 1;
            }
            if ($role == 'kassubag') {
                $sp2t->approval_kassubag = 1;
            }
            $sp2t->updated_at = date('Y-m-d H:i:s');
            if ($sp2t->save()) {
                $sp2tapp = Sp2t::find($id);
                if ($sp2tapp->approval_verifikatur == 1 && $sp2tapp->approval_kassubag == 1) {
                    $sp2tapp->status = 1;
                }
                $sp2tapp->save();
                return response()->json(['status'=> 'ok'], 200);
            } else {
                return response()->json(['status'=>'failed'], 500);
            }
        }
    }
}
