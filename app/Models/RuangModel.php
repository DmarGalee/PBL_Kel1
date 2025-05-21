<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuangModel extends Model
{
    protected $table = 'm_ruang';
    protected $primaryKey = 'ruang_id';
    protected $fillable = ['ruang_nama', 'lantai_id'];

    public function lantai()
    {
        return $this->belongsTo(LantaiModel::class, 'lantai_id', 'lantai_id');
    }
    
}
