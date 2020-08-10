<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sp2d extends Model
{
    protected $table = 'spd2';
    protected $dates = ['tgl_sp2d', 'created_at', 'updated_at'];

    public function scopeSearchNotaDinas($query, $q)
    {
        if ($q) {
            return $query->where('nomor_sp2d', 'LIKE', '%' . $q . '%');
        }
    }
}
