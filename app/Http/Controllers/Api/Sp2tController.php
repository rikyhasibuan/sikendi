<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Libraries\Common;
use App\Models\Sp2t;
use App\Models\Sp2tDetail;
use App\Models\Pelimpahan;
use App\Models\PelimpahanDetail;
use App\Models\Pegawai;
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
        $old_jml = $detail->nominal_transfer;
        $parent_id = $detail->sp2t_id;
        if ($detail->delete()) {
            $sp2t = Sp2t::find($parent_id);
            $jml = $sp2t->jumlah_transfer;
            $sisa = $sp2t->sisa_pelimpahan;
            $sp2t->jumlah_transfer = $jml - $old_jml;
            $sp2t->sisa_pelimpahan = $sisa + $old_jml;
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
            $anggaran = $sp2t->sisa_anggaran;
            $sp2t->jumlah_transfer = $jml + $detail->nominal_transfer;
            $sp2t->sisa_anggaran = $anggaran - $detail->nominal_transfer;
            $sp2t->sisa_pelimpahan = $sisa - ($jml + $detail->nominal_transfer);
            $sp2t->save();
            $payload = [
                'page' => 'SP2T',
                'message' => 'User dengan NIP '.$request->query('nip').' menambahkan data SP2T baru'
            ];
            $this->_common->generate_log($payload);
            return response()->json(['status'=>'ok'], 200);
        } else {
            return response()->json(['status'=>'failed'], 500);
        }
    }

    public function put_data(Request $request)
    {
        $sp2t = Sp2t::find($request['sp2t']);
        $detail = Sp2tDetail::find($request['id']);
        $old_jml = $detail->nominal_transfer;

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
            $sp2t->jumlah_transfer = $jml - $old_jml + $detail->nominal_transfer;
            $sp2t->sisa_pelimpahan = $sisa + $old_jml - ($jml + $detail->nominal_transfer);
            $sp2t->save();
            $payload = [
                'page' => 'SP2T',
                'message' => 'User dengan NIP '.$request->query('nip').' melakukan ubah data pada SP2T'
            ];
            $this->_common->generate_log($payload);
            return response()->json(['status'=>'ok'], 200);
        } else {
            return response()->json(['status'=>'failed'], 500);
        }
    }

    public function delete_nominal_data(Request $request)
    {
        $pelimpahan = PelimpahanDetail::find($request['id']);
        $jenis = $pelimpahan->jenis_pelimpahan;
        $jumlah = $pelimpahan->jumlah_pelimpahan;
        $pelimpahan_id = $pelimpahan->pelimpahan_id;
        if ($pelimpahan->delete()) {
            $parent = Pelimpahan::find($pelimpahan_id);
            if ($jenis == 'UP') {
                $parent->up = $parent->up - $jumlah;
            } elseif ($jenis == 'GU') {
                $parent->gu = $parent->gu - $jumlah;
            } elseif ($jenis == 'TU') {
                $parent->tu = $parent->tu - $jumlah;
            } elseif ($jenis == 'LS') {
                $parent->ls = $parent->ls - $jumlah;
            }
            $parent->jumlah_pelimpahan = $parent->jumlah_pelimpahan - $jumlah;
            $parent->sisa_sp2d = $parent->sisa_sp2d + $jumlah;
            $parent->save();
            $payload = [
                'page' => 'Pelimpahan',
                'message' => 'User dengan NIP '.$request->query('nip').' melakukan hapus data pada Pelimpahan'
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

    public function put_approval_data(Request $request)
    {
        $act = $request['act'];
        $type = $request['type'];
        $tab = $request['tab'];
        $dinasbop_id = $request['id'];

        if ($act == 'revision') {
            $approvalbop = DinasBopApproval::where('dinasbop_id', $dinasbop_id)->where('tab', $tab)->first();
            $column = $approvalbop[$type];
            array_push($column['catatan'], [
                'text'=>$request->input('catatan'),
                'date' => date('Y-m-d H:i:s')
            ]);

            $primary_id = $approvalbop['id'];

            $dinasboprevision = DinasBopApproval::find($primary_id);
            $dinasboprevision->{$type} = $column;
            $dinasboprevision->updated_at = date('Y-m-d H:i:s');

            if ($dinasboprevision->save()) {
                return response()->json(['status'=>'ok'], 200);
            } else {
                return response()->json(['status'=>'failed'], 500);
            }
        } elseif ($act == 'approve') {
            $approvalbop = DinasBopApproval::where('dinasbop_id', $dinasbop_id)->where('tab', $tab)->first();
            $column = $approvalbop[$type];
            $column['approval'] = 1;
            $primary_id = $approvalbop['id'];

            $dinasbopapproval = DinasBopApproval::find($primary_id);
            $dinasbopapproval->{$type} = $column;
            $dinasbopapproval->updated_at = date('Y-m-d H:i:s');
            if ($dinasbopapproval->save()) {
                $dinasboplock = DinasBopApproval::find($primary_id);
                $i = 0;
                if ($dinasboplock->inspektur['approval'] == 1) {
                    $i++;
                }
                if ($dinasboplock->sekretaris['approval'] == 1) {
                    $i++;
                }
                if ($dinasboplock->kassubag['approval'] == 1) {
                    $i++;
                }

                if ($i == 3) {
                    $dinasboplock->lock = 1;
                    if ($dinasboplock->save()) {
                        $x = 0;
                        $dinasboplockall = DinasBopApproval::where('dinasbop_id', $dinasbop_id)->get();
                        foreach ($dinasboplockall as $y) {
                            if ($y->lock == 1) {
                                $x++;
                            }
                        }

                        if ($x >= 8) {
                            DinasBop::where('id', $dinasbop_id)->update(['status' => 1]);
                        }
                    }
                }

                return response()->json(['status'=>'ok'], 200);
            } else {
                return response()->json(['status'=>'failed'], 500);
            }
        }
    }

    public function put_lock_data(Request $request)
    {
        $dinasbop = DinasBop::find($request['id']);
        $dinasbop->status = 1;
        if ($dinasbop->save()) {
            return response()->json(['status'=>'ok'], 200);
        } else {
            return response()->json(['status'=>'failed'], 500);
        }
    }
}
