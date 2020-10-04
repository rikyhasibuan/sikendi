<?php

namespace App\Exports;

use App\Models\Anggaran;
use App\Models\Kegiatan;
use App\Models\Belanja;
use App\Models\Sp2d;
use App\Models\Pelimpahan;
use App\Models\Sp2t;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AnggaranExport implements FromView, ShouldAutoSize
{
    protected $dari;

    function __construct($dari, $sampai, $bendahara)
    {
        $this->dari = explode('-', $dari);
    }

    public function view(): View
    {
        $sql_kegiatan = Kegiatan::searchBendahara($this->bendahara)->with('pegawai')->get();
        $output = [];
        $i = 0;
        $dari = $this->dari;
        return view('excel', ['bendahara' => $this->bendahara, 'dari' => $dari[1]]);

    }
}
