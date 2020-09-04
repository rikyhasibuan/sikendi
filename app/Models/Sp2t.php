<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sp2t extends Model
{
    protected $table = 'sp2t';
    protected $dates = ['tgl_nota_dinas', 'created_at', 'updated_at'];
    protected $fillable = [
        'nota_dinas',
        'tgl_nota_dinas',
        'bendahara',
        'jumlah_pelimpahan',
        'sisa_pelimpahan',
        'sisa_anggaran',
        'jumlah_transfer',
        'status'
    ];

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

    public function scopeSearchAwalPeriode($query, $start_date)
    {
        if ($start_date != '') {
            return $query->whereDate('tgl_nota_dinas', '>=', $start_date);
        }
    }

    public function scopeSearchAkhirPeriode($query, $end_date)
    {
        if ($end_date != '') {
            return $query->whereDate('tgl_nota_dinas', '<=', $end_date);
        }
    }
}
