<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Libraries\Common;
use App\Models\Sp2d;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Exception;

class Sp2dController extends Controller
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
            $sp2d = Sp2d::searchNomorSurat($_q)->orderBy('id', 'DESC')->paginate(10);
            return response()->json($sp2d, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show_data(Request $request)
    {
        return response()->json(Sp2d::find($request['id']), 200);
    }

    public function post_data(Request $request)
    {
        $check = Sp2d::where('nomor_sp2d', $request->input('nomor_sp2d'))->count();
        if ($check == 0) {
            $sp2d = new Sp2d();
            $sp2d->nota_dinas = $request->input('nota_dinas');
            $sp2d->tgl_nota_dinas = $request->input('tgl_nota_dinas');
            $sp2d->jumlah_pelimpahan = $request->input('jumlah_pelimpahan');
            $sp2d->status = 0;
            $sp2d->created_at = date('Y-m-d H:i:s');
            if ($sp2d->save()) {
                $payload = [
                'page' => 'SP2D',
                'message' => 'User dengan NIP '.$request->query('nip').' menambahkan data SP2D baru'
                ];
                $this->_common->generate_log($payload);
                return response()->json(['status'=>'ok'], 200);
            } else {
                return response()->json(['status'=>'failed'], 500);
            }
        } else {
            return response()->json(['status'=>'duplicate'], 200);
        }
    }

    public function put_data(Request $request)
    {
        $sp2d = Sp2d::find($request['id']);
        $sp2d->nota_dinas = $request->input('nota_dinas');
        $sp2d->tgl_nota_dinas = $request->input('tgl_nota_dinas');
        $sp2d->jumlah_pelimpahan = $request->input('jumlah_pelimpahan');
        $sp2d->updated_at = date('Y-m-d H:i:s');
        if ($sp2d->save()) {
            $payload = [
            'page' => 'SP2D',
            'message' => 'User dengan NIP '.$request->query('nip').' melakukan perubahan pada data SP2D'
            ];
            $this->_common->generate_log($payload);
            return response()->json(['status' => 'ok'], 200);
        } else {
            return response()->json(['status' => 'failed'], 500);
        }
    }

    public function delete_data(Request $request)
    {
        $sp2d = SP2D::find($request['id']);
        if ($sp2d->delete()) {
            $payload = [
            'page' => 'SP2D',
            'message' => 'User dengan NIP '.$request->query('nip').' melakukan hapus data pada SP2D'
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
