<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FasilitasModel extends Model
{
    use HasFactory;

    protected $table = 'm_fasilitas';
    protected $primaryKey = 'fasilitas_id';
    protected $guarded = ['fasilitas_id'];

    protected $fillable = [
        'ruang_id',
        'kategori_id',
        'fasilitas_kode',
        'deskripsi',
        'status'
    ];

    public function ruang()
    {
        return $this->belongsTo(RuangModel::class, 'ruang_id', 'ruang_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }
}