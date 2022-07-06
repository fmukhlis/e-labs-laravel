<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPemeriksaan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function pemeriksaan()
    {
        return $this->hasMany(Pemeriksaan::class);
    }
}
