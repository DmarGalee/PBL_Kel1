<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GedungModel extends Model
{
    protected $table = 'm_gedung';
    protected $primaryKey = 'gedung_id';
    protected $fillable = ['gedung_nama', 'gedung_kode', 'description'];
}
