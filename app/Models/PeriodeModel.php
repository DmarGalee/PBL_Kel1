<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodemODEL extends Model
{
    protected $table = 'm_periode';

    // Karena primary key bukan 'id', tapi 'periode_id'
    protected $primaryKey = 'periode_id';

    // Tipe primary key adalah string karena tipe kolom `year`
    protected $keyType = 'string';

    // Tidak auto-increment karena kolom year bukan auto-increment
    public $incrementing = false;

    // Tambahkan kolom-kolom yang bisa diisi
    protected $fillable = [
        'periode_id',
        'periode_name',
        'is_active',
    ];
}
