<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
 use Tymon\JWTAuth\Contracts\JWTSubject;
 use Illuminate\Database\Eloquent\Casts\Attribute;
 use App\Models\GedungModel;

class LantaiModel extends Model
{
    protected $table = 'm_lantai'; // Nama tabel di database
    protected $primaryKey = 'lantai_id'; // Primary key tabel

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'lantai_nomor',
        'deskripsi',
        'gedung_id',
    ];

    // Relasi dengan tabel gedung (jika ada model Gedung)
    public function gedung()
    {
        return $this->belongsTo(GedungModel::class, 'gedung_id', 'gedung_id');
    }
}