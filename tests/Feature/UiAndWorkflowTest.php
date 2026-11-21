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

test('public pages are accessible without login', function () {
    $this->get(route('perhitungan.hasil'))->assertOk()->assertSee('Hasil Rekomendasi Laptop Terbaik');
    $this->get(route('perhitungan.simulator'))->assertOk()->assertSee('Simulator Bobot Kriteria Interaktif');
    $this->get('/login')->assertOk()->assertSee('Login Pengelola SPK');
});

test('protected pages redirect unauthenticated users to login', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
    $this->get(route('kriteria.index'))->assertRedirect(route('login'));
    $this->get(route('alternatif.index'))->assertRedirect(route('login'));
    $this->get(route('penilaian.index'))->assertRedirect(route('login'));
});

test('authenticated admin can access dashboard and crud pages', function () {
    $admin = User::first();

    $this->actingAs($admin)->get(route('dashboard'))->assertOk()->assertSee('KuatRender');
    $this->actingAs($admin)->get(route('kriteria.index'))->assertOk()->assertSee('C1');
    $this->actingAs($admin)->get(route('alternatif.index'))->assertOk()->assertSee('A1');
    $this->actingAs($admin)->get(route('penilaian.index'))->assertOk()->assertSee('Matriks Penilaian');
});

test('adding an alternative automatically initializes penilaians with 0', function () {
    $admin = User::first();
    $kriteriasCount = Kriteria::count();

    $response = $this->actingAs($admin)->post(route('alternatif.store'), [
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
    $admin = User::first();
    $alt = Alternatif::first();
    $kriterias = Kriteria::all();

    $matrixData = [];
    foreach ($kriterias as $k) {
        $matrixData[$alt->id][$k->id] = 99.5;
    }

    $response = $this->actingAs($admin)->put(route('penilaian.batch'), [
        'matrix' => $matrixData,
    ]);

    $response->assertRedirect(route('penilaian.index'));

    $p = Penilaian::where('alternatif_id', $alt->id)->where('kriteria_id', $kriterias->first()->id)->first();
    expect((float)$p->nilai)->toBe(99.5);
});
