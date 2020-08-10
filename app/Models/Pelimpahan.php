<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelimpahan extends Model
{
  protected $table = 'pelimpahan';
  protected $dates = ['tgl_nota_dinas', 'created_at', 'updated_at'];

  public function tim()
  {
    return $this->hasMany('App\Models\PelimpahanDetail', 'pelimpahan_id', 'id');
  }

  public function scopeSearchNotaDinas($query, $q)
  {
    if ($q) return $query->where('nota_dinas', 'LIKE', '%' . $q . '%');
  }
}