<?php

namespace App\Http\Controllers\Api;

use App\Models\Penerima;
use App\Libraries\Common;
use Illuminate\Http\Request;
use Exception;
use App\Http\Controllers\Controller;

class PenerimaController extends Controller
{
    protected $_common;

    public function __construct()
    {
        $this->_common = new Common();
    }

    public function get_data(Request $request)
    {
        try {
            $_query = ($request['q'] !== '') ? $request['q'] : '';
            $penerima = Penerima::searchPenerima($_query)->orderBy('id', 'DESC')->paginate(10);
            return response()->json($penerima, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete_data(Request $request)
    {
        $penerima = Penerima::find($request['id']);
        if ($penerima->delete()) {
            $payload = [
                'page' => 'Penerima',
                'message' => 'User dengan NIP '.$request->query('nip').' melakukan hapus data pada penerima'
            ];
            $this->_common->generate_log($payload);
            return response()->json(['status' => 'ok'], 200);
        } else {
            return response()->json(['status' => 'failed'], 500);
        }
    }
}
