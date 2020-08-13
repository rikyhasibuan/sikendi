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
}
