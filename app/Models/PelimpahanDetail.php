<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PelimpahanDetail extends Model
{
    protected $table = 'pelimpahan_detail';
    protected $dates = [
      'created_at',
      'updated_at'
    ];

    public function pegawai()
    {
        return $this->belongsTo('App\Models\Pegawai', 'bendahara', 'id');
    }

    public function pelimpahan()
    {
        return $this->belongsTo('App\Models\Pelimpahan', 'pelimpahan_id');
    }
}
