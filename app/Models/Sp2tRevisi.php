<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sp2tRevisi extends Model
{
    protected $table = 'sp2t_revisi';
    protected $dates = ['created_at', 'updated_at'];

    public function sp2t()
    {
        return $this->belongsTo('App\Models\Pegawai', 'bendahara', 'id');
    }

    public function pegawai()
    {
        return $this->belongsTo('App\Models\Pegawai', 'nip', 'nip');
    }

    public function scopeSearchPegawai($query, $q)
    {
        $query->whereHas('pegawai', function ($query) use ($q) {
            $query->searchPegawai($q);
        });
    }
}
