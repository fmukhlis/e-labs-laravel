<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function periksa()
    {
        return $this->belongsToMany(Periksa::class);
    }

    public function kategoripemeriksaan()
    {
        return $this->belongsTo(KategoriPemeriksaan::class);
    }
}
