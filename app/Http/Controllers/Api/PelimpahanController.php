<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Libraries\Common;
use App\Models\Anggaran;
use App\Models\Pelimpahan;
use App\Models\Sp2d;
use App\Models\PelimpahanDetail;
use App\Models\Sp2t;
use App\Models\Pegawai;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Exception;

class PelimpahanController extends Controller
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
            $pelimpahan = Pelimpahan::searchNotaDinas($_q)
            ->searchAwalPeriode($_start)
            ->searchAkhirPeriode($_end)
            ->orderBy('id', 'DESC')
            ->paginate(10);
            return response()->json($pelimpahan, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show_data(Request $request)
    {
        return response()->json(Pelimpahan::find($request['id']), 200);
    }

    public function post_data(Request $request)
    {
        $sp2d = Sp2d::latest('id')->first();
        $check = Pelimpahan::where('nota_dinas', $request->input('nota_dinas'))->count();
        if ($check == 0) {
            $check2 = Pelimpahan::where('nomor_sp2d', $sp2d['nomor_sp2d'])->latest()->first();
            if ($check2 != null) {
                $pelimpahan = new Pelimpahan();
                $pelimpahan->tahun_anggaran = $request->input('tahun_anggaran');
                $pelimpahan->nomor_sp2d = $check2['nomor_sp2d'];
                $pelimpahan->nota_dinas = $request->input('nota_dinas');
                $pelimpahan->tgl_nota_dinas = $request->input('tgl_nota_dinas');
                $pelimpahan->jumlah_sp2d = $check2['sisa_sp2d'];
                $pelimpahan->sisa_sp2d = $check2['sisa_sp2d'];
                $pelimpahan->status = 0;
                $pelimpahan->created_at = date('Y-m-d H:i:s');
                if ($pelimpahan->save()) {
                    $payload = [
                        'page' => 'Pelimpahan Uang',
                        'message' => 'User dengan NIP '.$request->query('nip').' menambahkan data Pelimpahan baru'
                    ];
                    $this->_common->generate_log($payload);
                    return response()->json(['status'=>'ok'], 200);
                } else {
                    return response()->json(['status'=>'failed'], 500);
                }
            } else {
                $check2 = Pelimpahan::latest()->first();
                $pelimpahan = new Pelimpahan();
                $pelimpahan->tahun_anggaran = $request->input('tahun_anggaran');
                $pelimpahan->nomor_sp2d = $sp2d['nomor_sp2d'];
                $pelimpahan->nota_dinas = $request->input('nota_dinas');
                $pelimpahan->tgl_nota_dinas = $request->input('tgl_nota_dinas');
                $pelimpahan->jumlah_sp2d = $sp2d['jumlah_sp2d'] + $check2['sisa_sp2d'];
                $pelimpahan->sisa_sp2d = $sp2d['jumlah_sp2d'] + $check2['sisa_sp2d'];
                $pelimpahan->status = 0;
                $pelimpahan->created_at = date('Y-m-d H:i:s');
                if ($pelimpahan->save()) {
                    $payload = [
                        'page' => 'Pelimpahan Uang',
                        'message' => 'User dengan NIP '.$request->query('nip').' menambahkan data Pelimpahan baru'
                    ];
                    $this->_common->generate_log($payload);
                    return response()->json(['status'=>'ok'], 200);
                } else {
                    return response()->json(['status'=>'failed'], 500);
                }
            }
        } else {
            return response()->json(['status'=>'duplicate'], 200);
        }
    }

    public function put_data(Request $request)
    {
        $pelimpahan = Pelimpahan::find($request['id']);
        $pelimpahan->nota_dinas = $request->input('nota_dinas');
        $pelimpahan->tgl_nota_dinas = $request->input('tgl_nota_dinas');
        $pelimpahan->jumlah_pelimpahan = $request->input('jumlah_pelimpahan');
        $pelimpahan->updated_at = date('Y-m-d H:i:s');
        if ($pelimpahan->save()) {
            $payload = [
                'page' => 'Pelimpahan Uang',
                'message' => 'User dengan NIP '.$request->query('nip').' melakukan perubahan pada data Pelimpahan Uang'
            ];
            $this->_common->generate_log($payload);
            return response()->json(['status' => 'ok'], 200);
        } else {
            return response()->json(['status' => 'failed'], 500);
        }
    }

    public function delete_data(Request $request)
    {
        $pelimpahan = Pelimpahan::find($request['id']);
        if ($pelimpahan->delete()) {
            PelimpahanDetail::where('pelimpahan_id', $request['id'])->delete();
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

    public function post_nominal_data(Request $request)
    {
        $parent = Pelimpahan::find($request['pelimpahan']);
        $pelimpahan = new PelimpahanDetail();
        $pelimpahan->pelimpahan_id = $request['pelimpahan'];
        $pelimpahan->bendahara = $request->input('bendahara');
        $pelimpahan->jumlah_pelimpahan = $request->input('jumlah_pelimpahan');
        $pelimpahan->jenis_pelimpahan = $request->input('jenis_pelimpahan');
        $pelimpahan->sisa_sp2d = $parent->sisa_sp2d - $request->input('jumlah_pelimpahan');
        $pelimpahan->created_at = date('Y-m-d H:i:s');
        if ($pelimpahan->save()) {
            if ($pelimpahan->jenis_pelimpahan == 'UP') {
                $parent->up = $parent->up + $pelimpahan->jumlah_pelimpahan;
            } elseif ($pelimpahan->jenis_pelimpahan == 'GU') {
                $parent->gu = $parent->gu + $pelimpahan->jumlah_pelimpahan;
            } elseif ($pelimpahan->jenis_pelimpahan == 'TU') {
                $parent->tu = $parent->tu + $pelimpahan->jumlah_pelimpahan;
            } elseif ($pelimpahan->jenis_pelimpahan == 'LS') {
                $parent->ls = $parent->ls + $pelimpahan->jumlah_pelimpahan;
            }
            $parent->jumlah_pelimpahan = $parent->jumlah_pelimpahan + $pelimpahan->jumlah_pelimpahan;
            $parent->sisa_sp2d = $pelimpahan->sisa_sp2d;
            $parent->save();
            $payload = [
                'page' => 'Pelimpahan Uang',
                'message' => 'User dengan NIP '.$request->query('nip').' menambahkan data Pelimpahan baru'
            ];
            $this->_common->generate_log($payload);
            return response()->json(['status'=>'ok'], 200);
        } else {
            return response()->json(['status'=>'failed'], 500);
        }
    }

    public function put_nominal_data(Request $request)
    {
        $parent = Pelimpahan::find($request['pelimpahan']);
        $pelimpahan = PelimpahanDetail::find($request['id']);
        $old_amount = $pelimpahan->jumlah_pelimpahan;
        if ($pelimpahan->jenis_pelimpahan == $request->input('jenis_pelimpahan')) {
            if ($pelimpahan->jenis_pelimpahan == 'UP') {
                $parent->up = $parent->up - $old_amount + $request->input('jumlah_pelimpahan');
            } elseif ($pelimpahan->jenis_pelimpahan == 'GU') {
                $parent->gu = $parent->gu - $old_amount + $request->input('jumlah_pelimpahan');
            } elseif ($pelimpahan->jenis_pelimpahan == 'TU') {
                $parent->tu = $parent->tu - $old_amount + $request->input('jumlah_pelimpahan');
            } elseif ($pelimpahan->jenis_pelimpahan == 'LS') {
                $parent->ls = $parent->ls - $old_amount + $request->input('jumlah_pelimpahan');
            }
        } else {
            if ($pelimpahan->jenis_pelimpahan == 'UP') {
                $parent->up = $parent->up - $old_amount;
            } elseif ($pelimpahan->jenis_pelimpahan == 'GU') {
                $parent->gu = $parent->gu - $old_amount;
            } elseif ($pelimpahan->jenis_pelimpahan == 'TU') {
                $parent->tu = $parent->tu - $old_amount;
            } elseif ($pelimpahan->jenis_pelimpahan == 'LS') {
                $parent->ls = $parent->ls - $old_amount;
            }

            if ($request->input('jenis_pelimpahan') == 'UP') {
                $parent->up = $parent->up + $request->input('jumlah_pelimpahan');
            } elseif ($request->input('jenis_pelimpahan') == 'GU') {
                $parent->gu = $parent->gu + $request->input('jumlah_pelimpahan');
            } elseif ($request->input('jenis_pelimpahan') == 'TU') {
                $parent->tu = $parent->tu + $request->input('jumlah_pelimpahan');
            } elseif ($request->input('jenis_pelimpahan') == 'LS') {
                $parent->ls = $parent->ls + $request->input('jumlah_pelimpahan');
            }
        }

        $pelimpahan->pelimpahan_id = $request['pelimpahan'];
        $pelimpahan->bendahara = $request->input('bendahara');
        $pelimpahan->jumlah_pelimpahan = $request->input('jumlah_pelimpahan');
        $pelimpahan->jenis_pelimpahan = $request->input('jenis_pelimpahan');
        $pelimpahan->sisa_sp2d = $parent->sisa_sp2d + $old_amount - $request->input('jumlah_pelimpahan');
        $pelimpahan->created_at = date('Y-m-d H:i:s');
        if ($pelimpahan->save()) {
            $parent->jumlah_pelimpahan = $parent->jumlah_pelimpahan - $old_amount + $pelimpahan->jumlah_pelimpahan;
            $parent->sisa_sp2d = $pelimpahan->sisa_sp2d;
            $parent->save();
            $payload = [
                'page' => 'Pelimpahan Uang',
                'message' => 'User dengan NIP '.$request->query('nip').' melakukan ubah data pada Pelimpahan uang'
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

    public function send_nominal_data(Request $request)
    {
        $pelimpahan = Pelimpahan::find($request['pelimpahan']);
        $detail = PelimpahanDetail::where('pelimpahan_id', $request['pelimpahan'])->get();

        foreach ($detail as $v) {
            $kegiatan = Kegiatan::where('bendahara', $v->bendahara)->get();
            $total_anggaran = 0;
            $kegiatan_list = [];

            foreach ($kegiatan as $k) {
                $kegiatan_list[] = $k->id;
            }

            $pdetail = PelimpahanDetail::where('bendahara', $v->bendahara)->sum('jumlah_pelimpahan');
            $anggaran = Anggaran::whereIn('kegiatan_id', $kegiatan_list)->sum('jumlah');
            $total_anggaran = $anggaran - $pdetail;

            Sp2t::updateOrCreate(
                [
                    'nota_dinas' => $pelimpahan->nota_dinas,
                    'tgl_nota_dinas' => $pelimpahan->tgl_nota_dinas,
                    'bendahara' => $v->bendahara,
                    'jumlah_pelimpahan' => $v->jumlah_pelimpahan,
                    'status' => 0
                ],
                [
                    'jumlah_transfer' => 0,
                    'jumlah_pelimpahan' => $v->jumlah_pelimpahan,
                    'sisa_pelimpahan' => $v->jumlah_pelimpahan,
                    'sisa_anggaran' => $total_anggaran,
                    'status' => 0
                ]
            );
        }
        $pelimpahan->status = 1;
        $pelimpahan->save();
        return response()->json(['status' => 'ok'], 200);
    }

    public function get_print(Request $request)
    {
        try {
            $_id = isset($request['id']) ? $request['id'] : '';

            $pelimpahan = Pelimpahan::find($_id);
            $detail = PelimpahanDetail::with('pegawai')->where('pelimpahan_id', $_id)->get();
            $view = View::make('pelimpahan.print', ['pelimpahan' => $pelimpahan, 'detail' => $detail]);
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
