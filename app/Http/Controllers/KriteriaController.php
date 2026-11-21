<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KriteriaController extends Controller
{
    public function index(): View
    {
        $kriterias = Kriteria::orderBy('kode_kriteria')->get();
        $totalBobotAwal = $kriterias->sum('bobot_awal');

        // Tambahkan bobot ternormalisasi ke setiap objek
        $kriterias->transform(function ($kriteria) use ($totalBobotAwal) {
            $norm = $totalBobotAwal > 0 ? ($kriteria->bobot_awal / $totalBobotAwal) : 0;
            $kriteria->bobot_normalisasi = $norm;
            $kriteria->bobot_normalisasi_format = number_format($norm, 4);
            $kriteria->persentase_bobot = number_format($norm * 100, 2) . '%';
            return $kriteria;
        });

        return view('kriteria.index', [
            'kriterias' => $kriterias,
            'totalBobotAwal' => $totalBobotAwal,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_kriteria' => ['required', 'string', 'max:20', 'unique:kriterias,kode_kriteria'],
            'nama_kriteria' => ['required', 'string', 'max:150'],
            'satuan' => ['required', 'string', 'max:50'],
            'sifat' => ['required', Rule::in(['benefit', 'cost'])],
            'bobot_awal' => ['required', 'numeric', 'min:0'],
        ]);

        $kriteria = Kriteria::create($validated);

        // Inisialisasi data penilaian 0 untuk setiap alternatif yang sudah ada
        $alternatifs = Alternatif::all();
        foreach ($alternatifs as $alternatif) {
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

        return redirect()->route('kriteria.index')
            ->with('success', "Kriteria {$kriteria->kode_kriteria} ({$kriteria->nama_kriteria}) berhasil ditambahkan.");
    }

    public function update(Request $request, Kriteria $kriteria): RedirectResponse
    {
        $validated = $request->validate([
            'kode_kriteria' => ['required', 'string', 'max:20', Rule::unique('kriterias', 'kode_kriteria')->ignore($kriteria->id)],
            'nama_kriteria' => ['required', 'string', 'max:150'],
            'satuan' => ['required', 'string', 'max:50'],
            'sifat' => ['required', Rule::in(['benefit', 'cost'])],
            'bobot_awal' => ['required', 'numeric', 'min:0'],
        ]);

        $kriteria->update($validated);

        return redirect()->route('kriteria.index')
            ->with('success', "Kriteria {$kriteria->kode_kriteria} berhasil diperbarui.");
    }

    public function destroy(Kriteria $kriteria): RedirectResponse
    {
        $kode = $kriteria->kode_kriteria;
        $kriteria->delete();

        return redirect()->route('kriteria.index')
            ->with('success', "Kriteria {$kode} berhasil dihapus.");
    }
}
