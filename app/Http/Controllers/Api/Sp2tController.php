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
            $sp2t = Sp2t::searchNotaDinas($_q)->with('pegawai')->orderBy('id', 'DESC')->paginate(10);
            return response()->json($sp2t, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show_data(Request $request)
    {
        return response()->json(Pelimpahan::find($request['id']), 200);
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
        echo "<pre>";
        print_r($request);
        exit;
        /*$parent = Pelimpahan::find($request['pelimpahan']);
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
        }*/
    }

    public function put_nominal_data(Request $request)
    {
        $parent = Pelimpahan::find($request['pelimpahan']);
        $pelimpahan = PelimpahanDetail::find($request['id']);

        // ambil dulu data nominal lama
        $old_amount = $pelimpahan->jumlah_pelimpahan;

        // kalau jenis pelimpahan yang lama sama dengan yang baru
        if ($pelimpahan->jenis_pelimpahan == $request->input('jenis_pelimpahan')) {
            if ($pelimpahan->jenis_pelimpahan == 'UP') {
                $parent->up = $parent->up - $old_amount + $pelimpahan->jumlah_pelimpahan;
            } elseif ($pelimpahan->jenis_pelimpahan == 'GU') {
                $parent->gu = $parent->gu - $old_amount + $pelimpahan->jumlah_pelimpahan;
            } elseif ($pelimpahan->jenis_pelimpahan == 'TU') {
                $parent->tu = $parent->tu - $old_amount + $pelimpahan->jumlah_pelimpahan;
            } elseif ($pelimpahan->jenis_pelimpahan == 'LS') {
                $parent->ls = $parent->ls - $old_amount + $pelimpahan->jumlah_pelimpahan;
            }
        // ini kalau beda jenisnya.
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

    public function get_print(Request $request)
    {
        try {
            $_id = isset($request['id']) ? $request['id'] : '';
            $_type = isset($request['type']) ? $request['type'] : '';

            $view = '';
            switch ($_type) {
                case 'tim':
                    $dinasboptim = DinasBopTim::with('dinasbop')->find($_id);
                    $view = View::make('dinasbop.print_tim.sp', ['dinasboptim'=>$dinasboptim]);
                    break;
                case 'driver':
                    $dinasbopdriver = DinasBopDriver::with('dinasbop')->find($_id);
                    $view = View::make('dinasbop.print_driver.sp', ['dinasbopdriver'=>$dinasbopdriver]);
                    break;
                case 'inspektur':
                    $dinasbopinspektur = DinasBopInspektur::with('dinasbop')->find($_id);
                    $view = View::make('dinasbop.print_inspektur.sp', ['dinasbopinspektur'=>$dinasbopinspektur]);
                    break;
                case 'sekretaris':
                    $dinasbopsekretaris = DinasBopSekretaris::with('dinasbop')->find($_id);
                    $view = View::make('dinasbop.print_sekretaris.sp', ['dinasbopsekretaris'=>$dinasbopsekretaris]);
                    break;
                case 'reviu':
                    $dinasbopreviu = DinasBopReviu::with('dinasbop')->find($_id);
                    $view = View::make('dinasbop.print_reviu.sp', ['dinasbopreviu'=>$dinasbopreviu]);
                    break;
                case 'supervisi':
                    $dinasbopsupervisi = DinasBopSupervisi::with('dinasbop')->find($_id);
                    $view = View::make('dinasbop.print_supervisi.sp', ['dinasbopsupervisi'=>$dinasbopsupervisi]);
                    break;
                case 'pengumpuldata':
                    $dinasboppengumpuldata = DinasBopPengumpulDataTim::with('dinasboppengumpuldata')->find($_id);
                    $view = View::make('dinasbop.print_pengumpuldata.sp', ['timpengumpuldata'=>$dinasboppengumpuldata]);
                    break;
                case 'administrasi':
                    $dinasbopadministrasi = DinasBopAdministrasiTim::with('dinasbopadministrasi')->find($_id);
                    $view = View::make('dinasbop.print_administrasi.sp', ['timadministrasi'=>$dinasbopadministrasi]);
                    break;
                case 'custom':
                    $dinasbopcustom = DinasBopCustomTim::with('dinasbop')->find($_id);
                    $view = View::make('dinasbop.print_custom.sp', ['dinasbopcustom'=>$dinasbopcustom]);
                    break;
            }

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
