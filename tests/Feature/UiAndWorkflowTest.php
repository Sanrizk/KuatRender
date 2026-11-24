<?php

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('all pages are accessible directly without login', function () {
    $this->get('/')->assertOk()->assertSee('KuatRender');
    $this->get(route('dashboard'))->assertOk()->assertSee('KuatRender');
    $this->get(route('kriteria.index'))->assertOk()->assertSee('C1');
    $this->get(route('alternatif.index'))->assertOk()->assertSee('A1');
    $this->get(route('penilaian.index'))->assertOk()->assertSee('Matriks Penilaian');
    $this->get(route('perhitungan.hasil'))->assertOk()->assertSee('Detail Perhitungan Algoritma SMART');
    $this->get('/perhitungan')->assertOk()->assertSee('Detail Perhitungan Algoritma SMART');
    $this->get(route('perhitungan.simulator'))->assertOk()->assertSee('Simulator Bobot Kriteria Interaktif');
});

test('api simulator calculate returns json rankings', function () {
    $response = $this->postJson(route('api.simulator.calculate'), [
        'weights' => ['C1' => 30, 'C2' => 20],
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'ringkasan',
            'kriterias',
            'hasil_akhir',
        ]);
});

test('adding an alternative automatically initializes penilaians with 0', function () {
    $kriteriasCount = Kriteria::count();

    $response = $this->post(route('alternatif.store'), [
        'kode_alternatif' => 'A5',
        'nama_laptop' => 'Dell Precision 7680',
        'merek' => 'Dell',
        'spesifikasi_ringkas' => 'Workstation laptop premium.',
    ]);

    $response->assertRedirect(route('alternatif.index'));

    $newAlt = Alternatif::where('kode_alternatif', 'A5')->first();
    expect($newAlt)->not->toBeNull();
    expect($newAlt->penilaians()->count())->toBe($kriteriasCount);
    expect((float)$newAlt->penilaians()->first()->nilai)->toBe(0.0);
});

test('batch update matrix saves values accurately', function () {
    $alt = Alternatif::first();
    $kriterias = Kriteria::all();

    $matrixData = [];
    foreach ($kriterias as $k) {
        $matrixData[$alt->id][$k->id] = 99.5;
    }

    $response = $this->put(route('penilaian.batch'), [
        'matrix' => $matrixData,
    ]);

    $response->assertRedirect(route('penilaian.index'));

    $p = Penilaian::where('alternatif_id', $alt->id)->where('kriteria_id', $kriterias->first()->id)->first();
    expect((float)$p->nilai)->toBe(99.5);
});
