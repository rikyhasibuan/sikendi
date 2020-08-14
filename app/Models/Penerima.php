<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penerima extends Model
{
  protected $table = 'penerima';
  protected $dates = ['created_at', 'updated_at'];

  public function scopeSearchPenerima($query, $q)
  {
    if ($q) {
      return $query->where('nama_penerima', 'LIKE', '%' . $q . '%')->orWhere('norek', 'LIKE', '%' . $q . '%');
    }
  }
}
