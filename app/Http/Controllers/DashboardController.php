<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Services\SmartCalculationService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(SmartCalculationService $calculationService): View
    {
        $kriteriasCount = Kriteria::count();
        $alternatifsCount = Alternatif::count();
        $penilaiansCount = Penilaian::count();

        $calculation = $calculationService->calculate();
        $topRanking = array_slice($calculation['hasil_akhir'], 0, 3);

        return view('dashboard', [
            'kriteriasCount' => $kriteriasCount,
            'alternatifsCount' => $alternatifsCount,
            'penilaiansCount' => $penilaiansCount,
            'calculation' => $calculation,
            'topRanking' => $topRanking,
        ]);
    }
}
