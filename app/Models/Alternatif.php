<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alternatif extends Model
{
    use HasFactory;

    protected $table = 'alternatifs';

    protected $fillable = [
        'kode_alternatif',
        'nama_laptop',
        'merek',
        'spesifikasi_ringkas',
    ];

    public function penilaians(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'alternatif_id');
    }
}
