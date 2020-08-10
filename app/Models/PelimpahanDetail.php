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

  public function pelimpahan()
  {
    return $this->belongsTo('App\Models\Pelimpahan', 'pelimpahan_id');
  }

  public function program()
  {
    return $this->belongsTo('App\Models\Program', 'program_id');
  }

  public function kegiatan()
  {
    return $this->belongsTo('App\Models\Kegiatan', 'kegiatan_id');
  }
}
