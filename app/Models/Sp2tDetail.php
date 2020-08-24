<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sp2tDetail extends Model
{
    protected $table = 'sp2t_detail';
    protected $dates = [
      'created_at',
      'updated_at'
    ];

    public function pegawai()
    {
        return $this->belongsTo('App\Models\Pegawai', 'bendahara', 'id');
    }

    public function sp2t()
    {
        return $this->belongsTo('App\Models\Sp2t', 'sp2t_id');
    }
}
