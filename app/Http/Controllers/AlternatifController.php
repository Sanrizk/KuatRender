<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AlternatifController extends Controller
{
    public function index(): View
    {
        $alternatifs = Alternatif::with('penilaians.kriteria')->orderBy('kode_alternatif')->get();

        return view('alternatif.index', [
            'alternatifs' => $alternatifs,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_alternatif' => ['required', 'string', 'max:20', 'unique:alternatifs,kode_alternatif'],
            'nama_laptop' => ['required', 'string', 'max:200'],
            'merek' => ['required', 'string', 'max:100'],
            'spesifikasi_ringkas' => ['nullable', 'string', 'max:1000'],
        ]);

        $alternatif = Alternatif::create($validated);

        // Inisialisasi entri nilai default 0 pada tabel penilaians untuk semua kriteria yang aktif
        $kriterias = Kriteria::all();
        foreach ($kriterias as $kriteria) {
            Penilaian::firstOrCreate(
                [
                    'alternatif_id' => $alternatif->id,
                    'kriteria_id' => $kriteria->id,
                ],
                [
                    'nilai' => 0.0000,
                ]
            );
        }

        return redirect()->route('alternatif.index')
            ->with('success', "Alternatif {$alternatif->kode_alternatif} ({$alternatif->nama_laptop}) berhasil ditambahkan dengan inisialisasi penilaian default.");
    }

    public function update(Request $request, Alternatif $alternatif): RedirectResponse
    {
        $validated = $request->validate([
            'kode_alternatif' => ['required', 'string', 'max:20', Rule::unique('alternatifs', 'kode_alternatif')->ignore($alternatif->id)],
            'nama_laptop' => ['required', 'string', 'max:200'],
            'merek' => ['required', 'string', 'max:100'],
            'spesifikasi_ringkas' => ['nullable', 'string', 'max:1000'],
        ]);

        $alternatif->update($validated);

        return redirect()->route('alternatif.index')
            ->with('success', "Alternatif {$alternatif->kode_alternatif} berhasil diperbarui.");
    }

    public function destroy(Alternatif $alternatif): RedirectResponse
    {
        $kode = $alternatif->kode_alternatif;
        $alternatif->delete();

        return redirect()->route('alternatif.index')
            ->with('success', "Alternatif {$kode} berhasil dihapus beserta data penilaians terkait.");
    }
}
