<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Services\SmartCalculationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PerhitunganController extends Controller
{
    public function hasil(SmartCalculationService $service): View
    {
        $hasil = $service->calculate();

        return view('perhitungan.hasil', [
            'hasil' => $hasil,
        ]);
    }

    public function simulator(Request $request, SmartCalculationService $service): View
    {
        $kriterias = Kriteria::orderBy('kode_kriteria')->get();

        $simulatedWeights = $request->input('weights', []);
        $cleanedWeights = [];

        if (is_array($simulatedWeights) && !empty($simulatedWeights)) {
            foreach ($simulatedWeights as $id => $val) {
                if (is_numeric($val) && (float) $val >= 0) {
                    $cleanedWeights[(int) $id] = (float) $val;
                }
            }
        }

        $hasil = $service->calculate($cleanedWeights);

        return view('perhitungan.simulator', [
            'kriterias' => $kriterias,
            'simulatedWeights' => $cleanedWeights,
            'hasil' => $hasil,
        ]);
    }
}
