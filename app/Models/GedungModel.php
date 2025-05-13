<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GedungModel extends Model
{
    protected $table = 'm_gedung'; // Nama tabel di database
    protected $primaryKey = 'gedung_id'; // Primary key tabel

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'gedung_kode',
        'gedung_nama',
    ];

    // Otomatis mengelola timestamp created_at dan updated_at
    public $timestamps = true;
}