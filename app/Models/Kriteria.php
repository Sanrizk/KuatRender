<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriterias';

    protected $fillable = [
        'kode_kriteria',
        'nama_kriteria',
        'satuan',
        'sifat',
        'bobot_awal',
    ];

    protected function casts(): array
    {
        return [
            'bobot_awal' => 'decimal:2',
        ];
    }

    public function penilaians(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'kriteria_id');
    }
}
