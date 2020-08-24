<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sp2t extends Model
{
    protected $table = 'sp2t';
    protected $dates = ['tgl_nota_dinas', 'created_at', 'updated_at'];

    public function detail()
    {
        return $this->hasMany('App\Models\Sp2tDetail', 'sp2t_id', 'id');
    }

    public function pegawai()
    {
        return $this->belongsTo('App\Models\Pegawai', 'bendahara', 'id');
    }

    public function scopeSearchNotaDinas($query, $q)
    {
        if ($q) {
            return $query->where('nota_dinas', 'LIKE', '%' . $q . '%');
        }
    }
}
