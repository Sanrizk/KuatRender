<?php

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Services\SmartCalculationService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('smart calculation service calculates weights, utility, and rankings correctly', function () {
    $service = new SmartCalculationService();
    $result = $service->calculate();

    // 1. Verifikasi Ringkasan
    expect($result['ringkasan']['total_kriteria'])->toBe(6);
    expect($result['ringkasan']['total_alternatif'])->toBe(4);
    expect($result['ringkasan']['total_bobot_awal'])->toBe(100.0);
    expect($result['ringkasan']['total_bobot_normalisasi'])->toBe(1.0);

    // 2. Verifikasi Nilai Ekstrem
    $c1 = Kriteria::where('kode_kriteria', 'C1')->first();
    $c6 = Kriteria::where('kode_kriteria', 'C6')->first();

    expect($result['nilai_ekstrem'][$c1->id]['c_min'])->toBe(8.0);
    expect($result['nilai_ekstrem'][$c1->id]['c_max'])->toBe(12.0);

    expect($result['nilai_ekstrem'][$c6->id]['c_min'])->toBe(170.0);
    expect($result['nilai_ekstrem'][$c6->id]['c_max'])->toBe(330.0);

    // 3. Verifikasi Peringkat Sesuai Studi Kasus
    $ranking = $result['hasil_akhir'];

    expect($ranking[0]['kode_alternatif'])->toBe('A1');
    expect($ranking[0]['peringkat'])->toBe(1);
    expect($ranking[0]['nilai_akhir'])->toBeGreaterThan(0.61);
    expect($ranking[0]['nilai_akhir'])->toBeLessThan(0.62);

    expect($ranking[1]['kode_alternatif'])->toBe('A3');
    expect($ranking[1]['peringkat'])->toBe(2);
    expect($ranking[1]['nilai_akhir'])->toBeGreaterThan(0.49);
    expect($ranking[1]['nilai_akhir'])->toBeLessThan(0.50);

    expect($ranking[2]['kode_alternatif'])->toBe('A4');
    expect($ranking[2]['peringkat'])->toBe(3);
    expect($ranking[2]['nilai_akhir'])->toBeGreaterThan(0.39);
    expect($ranking[2]['nilai_akhir'])->toBeLessThan(0.40);

    expect($ranking[3]['kode_alternatif'])->toBe('A2');
    expect($ranking[3]['peringkat'])->toBe(4);
    expect($ranking[3]['nilai_akhir'])->toBeGreaterThan(0.18);
    expect($ranking[3]['nilai_akhir'])->toBeLessThan(0.19);
});

test('smart calculation service supports custom simulation weights', function () {
    $service = new SmartCalculationService();
    $simulasiResult = $service->calculate(['C1' => 50, 'C6' => 5]);

    expect($simulasiResult['ringkasan']['is_simulasi'])->toBeTrue();
    expect($simulasiResult['ringkasan']['total_bobot_awal'])->toBe(115.0);
    expect($simulasiResult['hasil_akhir'])->toBeArray();
});
