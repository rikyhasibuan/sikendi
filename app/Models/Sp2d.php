<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sp2d extends Model
{
    protected $table = 'sp2d';
    protected $dates = ['tgl_sp2d', 'created_at', 'updated_at'];

    public function scopeSearchSp2d($query, $q)
    {
        if ($q) {
            return $query->where('nomor_sp2d', 'LIKE', '%' . $q . '%');
        }
    }

    public function scopeSearchAwalPeriode($query, $start_date)
    {
        if ($start_date != '') {
            return $query->whereDate('tgl_sp2d', '>=', $start_date);
        }
    }

    public function scopeSearchAkhirPeriode($query, $end_date)
    {
        if ($end_date != '') {
            return $query->whereDate('tgl_sp2d', '<=', $end_date);
        }
    }
}
