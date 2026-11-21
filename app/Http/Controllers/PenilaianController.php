<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenilaianController extends Controller
{
    public function index(): View
    {
        $kriterias = Kriteria::orderBy('kode_kriteria')->get();
        $alternatifs = Alternatif::with('penilaians')->orderBy('kode_alternatif')->get();

        // Pastikan setiap pasangan (alternatif, kriteria) memiliki row penilaian
        foreach ($alternatifs as $alternatif) {
            $existingKriteriaIds = $alternatif->penilaians->pluck('kriteria_id')->all();
            foreach ($kriterias as $kriteria) {
                if (!in_array($kriteria->id, $existingKriteriaIds)) {
                    $newPenilaian = Penilaian::create([
                        'alternatif_id' => $alternatif->id,
                        'kriteria_id' => $kriteria->id,
                        'nilai' => 0.0000,
                    ]);
                    $alternatif->penilaians->push($newPenilaian);
                }
            }
        }

        return view('penilaian.index', [
            'kriterias' => $kriterias,
            'alternatifs' => $alternatifs,
        ]);
    }

    public function batchUpdate(Request $request): RedirectResponse
    {
        // Validasi struktur matriks: matrix[alternatif_id][kriteria_id] = numeric|min:0
        $validated = $request->validate([
            'matrix' => ['required', 'array'],
            'matrix.*' => ['required', 'array'],
            'matrix.*.*' => ['required', 'numeric', 'min:0'],
        ], [
            'matrix.*.*.required' => 'Seluruh nilai matriks wajib diisi.',
            'matrix.*.*.numeric' => 'Nilai matriks harus berupa angka yang valid.',
            'matrix.*.*.min' => 'Nilai matriks tidak boleh bernilai negatif.',
        ]);

        foreach ($validated['matrix'] as $alternatifId => $kriteriaValues) {
            foreach ($kriteriaValues as $kriteriaId => $nilai) {
                Penilaian::updateOrCreate(
                    [
                        'alternatif_id' => $alternatifId,
                        'kriteria_id' => $kriteriaId,
                    ],
                    [
                        'nilai' => $nilai,
                    ]
                );
            }
        }

        return redirect()->route('penilaian.index')
            ->with('success', 'Matriks penilaian berhasil diperbarui secara keseluruhan.');
    }
}
