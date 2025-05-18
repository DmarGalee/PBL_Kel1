<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    use HasFactory;

    protected $table = 'gedung';
    protected $primaryKey = 'gedung_id';
    public $timestamps = true;

    protected $fillable = [
        'gedung_kode',
        'gedung_nama',
    ];
}
