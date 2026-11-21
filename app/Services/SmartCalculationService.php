<?php

namespace App\Services;

use App\Models\Alternatif;
use App\Models\Kriteria;
use Illuminate\Support\Collection;

class SmartCalculationService
{
    /**
     * Jalankan seluruh tahapan perhitungan SMART.
     *
     * @param array<int|string, float|int> $overrideWeights Array opsional untuk simulasi bobot [kriteria_id => bobot_baru] atau [kode_kriteria => bobot_baru]
     * @return array<string, mixed>
     */
    public function calculate(array $overrideWeights = []): array
    {
        $kriterias = Kriteria::orderBy('kode_kriteria')->get();
        $alternatifs = Alternatif::with(['penilaians' => function ($query) {
            $query->with('kriteria');
        }])->orderBy('kode_alternatif')->get();

        // 1. Normalisasi Bobot Kriteria
        $stepBobot = $this->normalizeWeights($kriterias, $overrideWeights);

        // 2. Matriks Keputusan Awal & Ekstrem Min / Max
        $stepMatriksAwal = $this->buildInitialMatrix($kriterias, $alternatifs);

        // 3. Matriks Utilitas
        $stepUtilitas = $this->calculateUtilityMatrix($kriterias, $alternatifs, $stepMatriksAwal['nilai_ekstrem']);

        // 4. Nilai Akhir & Perankingan
        $stepRanking = $this->calculateFinalScoresAndRanking(
            $alternatifs,
            $kriterias,
            $stepBobot['kriterias'],
            $stepUtilitas['matriks_utilitas']
        );

        return [
            'ringkasan' => [
                'total_kriteria' => $kriterias->count(),
                'total_alternatif' => $alternatifs->count(),
                'total_bobot_awal' => $stepBobot['total_bobot_awal'],
                'total_bobot_normalisasi' => $stepBobot['total_bobot_normalisasi'],
                'is_simulasi' => !empty($overrideWeights),
            ],
            'kriterias' => $stepBobot['kriterias'],
            'matriks_keputusan' => $stepMatriksAwal['matriks'],
            'nilai_ekstrem' => $stepMatriksAwal['nilai_ekstrem'],
            'matriks_utilitas' => $stepUtilitas['matriks_utilitas'],
            'kontribusi_skor' => $stepRanking['kontribusi_skor'],
            'hasil_akhir' => $stepRanking['ranking'],
        ];
    }

    /**
     * Langkah 1: Normalisasi Bobot Kriteria (w_j = bobot_j / sum(bobot))
     *
     * @param Collection<int, Kriteria> $kriterias
     * @param array<int|string, float|int> $overrideWeights
     * @return array<string, mixed>
     */
    public function normalizeWeights(Collection $kriterias, array $overrideWeights = []): array
    {
        $items = [];
        $totalBobotAwal = 0.0;

        foreach ($kriterias as $kriteria) {
            // Cek apakah ada bobot override (berdasarkan ID atau kode kriteria)
            $bobotAwal = (float) $kriteria->bobot_awal;
            if (array_key_exists($kriteria->id, $overrideWeights)) {
                $bobotAwal = (float) $overrideWeights[$kriteria->id];
            } elseif (array_key_exists($kriteria->kode_kriteria, $overrideWeights)) {
                $bobotAwal = (float) $overrideWeights[$kriteria->kode_kriteria];
            }

            $totalBobotAwal += $bobotAwal;
            $items[$kriteria->id] = [
                'id' => $kriteria->id,
                'kode_kriteria' => $kriteria->kode_kriteria,
                'nama_kriteria' => $kriteria->nama_kriteria,
                'satuan' => $kriteria->satuan,
                'sifat' => $kriteria->sifat,
                'bobot_awal' => $bobotAwal,
                'bobot_awal_format' => number_format($bobotAwal, 2),
            ];
        }

        $totalBobotNormalisasi = 0.0;
        foreach ($items as $id => $item) {
            $bobotNormalisasi = $totalBobotAwal > 0 ? ($item['bobot_awal'] / $totalBobotAwal) : 0.0;
            $items[$id]['bobot_normalisasi'] = $bobotNormalisasi;
            $items[$id]['bobot_normalisasi_raw'] = round($bobotNormalisasi, 6);
            $items[$id]['bobot_normalisasi_format'] = number_format($bobotNormalisasi, 4);
            $items[$id]['persentase_bobot'] = number_format($bobotNormalisasi * 100, 2) . '%';
            $totalBobotNormalisasi += $bobotNormalisasi;
        }

        return [
            'total_bobot_awal' => $totalBobotAwal,
            'total_bobot_normalisasi' => round($totalBobotNormalisasi, 4),
            'kriterias' => $items,
        ];
    }

    /**
     * Langkah 2: Membangun Matriks Keputusan Awal & Menghitung Nilai Ekstrem (Min & Max)
     *
     * @param Collection<int, Kriteria> $kriterias
     * @param Collection<int, Alternatif> $alternatifs
     * @return array<string, mixed>
     */
    public function buildInitialMatrix(Collection $kriterias, Collection $alternatifs): array
    {
        $matriks = [];
        $kriteriaValues = [];

        foreach ($kriterias as $kriteria) {
            $kriteriaValues[$kriteria->id] = [];
        }

        foreach ($alternatifs as $alternatif) {
            $row = [
                'alternatif_id' => $alternatif->id,
                'kode_alternatif' => $alternatif->kode_alternatif,
                'nama_laptop' => $alternatif->nama_laptop,
                'merek' => $alternatif->merek,
                'nilai_kriteria' => [],
            ];

            $penilaiansByKriteria = $alternatif->penilaians->keyBy('kriteria_id');

            foreach ($kriterias as $kriteria) {
                $penilaian = $penilaiansByKriteria->get($kriteria->id);
                $nilai = $penilaian ? (float) $penilaian->nilai : 0.0;

                $row['nilai_kriteria'][$kriteria->id] = [
                    'kode_kriteria' => $kriteria->kode_kriteria,
                    'nilai' => $nilai,
                    'nilai_format' => number_format($nilai, 2),
                    'satuan' => $kriteria->satuan,
                ];

                $kriteriaValues[$kriteria->id][] = $nilai;
            }

            $matriks[$alternatif->id] = $row;
        }

        // Tentukan C_min dan C_max untuk tiap kriteria
        $nilaiEkstrem = [];
        foreach ($kriterias as $kriteria) {
            $vals = $kriteriaValues[$kriteria->id];
            $min = !empty($vals) ? min($vals) : 0.0;
            $max = !empty($vals) ? max($vals) : 0.0;

            $nilaiEkstrem[$kriteria->id] = [
                'id' => $kriteria->id,
                'kode_kriteria' => $kriteria->kode_kriteria,
                'nama_kriteria' => $kriteria->nama_kriteria,
                'sifat' => $kriteria->sifat,
                'satuan' => $kriteria->satuan,
                'c_min' => $min,
                'c_max' => $max,
                'c_min_format' => number_format($min, 2),
                'c_max_format' => number_format($max, 2),
                'range' => ($max - $min),
            ];
        }

        return [
            'matriks' => $matriks,
            'nilai_ekstrem' => $nilaiEkstrem,
        ];
    }

    /**
     * Langkah 3: Menghitung Matriks Nilai Utilitas u_ij
     *
     * Benefit: u_ij = (x_ij - C_min) / (C_max - C_min)
     * Cost:    u_ij = (C_max - x_ij) / (C_max - C_min)
     *
     * @param Collection<int, Kriteria> $kriterias
     * @param Collection<int, Alternatif> $alternatifs
     * @param array<int, array<string, mixed>> $nilaiEkstrem
     * @return array<string, mixed>
     */
    public function calculateUtilityMatrix(Collection $kriterias, Collection $alternatifs, array $nilaiEkstrem): array
    {
        $matriksUtilitas = [];

        foreach ($alternatifs as $alternatif) {
            $row = [
                'alternatif_id' => $alternatif->id,
                'kode_alternatif' => $alternatif->kode_alternatif,
                'nama_laptop' => $alternatif->nama_laptop,
                'merek' => $alternatif->merek,
                'utilitas' => [],
            ];

            $penilaiansByKriteria = $alternatif->penilaians->keyBy('kriteria_id');

            foreach ($kriterias as $kriteria) {
                $penilaian = $penilaiansByKriteria->get($kriteria->id);
                $x = $penilaian ? (float) $penilaian->nilai : 0.0;

                $cMin = (float) $nilaiEkstrem[$kriteria->id]['c_min'];
                $cMax = (float) $nilaiEkstrem[$kriteria->id]['c_max'];
                $range = $cMax - $cMin;

                if ($range == 0.0) {
                    $u = 0.0;
                } elseif ($kriteria->sifat === 'benefit') {
                    $u = ($x - $cMin) / $range;
                } else {
                    // cost
                    $u = ($cMax - $x) / $range;
                }

                $uInternal = round($u, 6);

                $row['utilitas'][$kriteria->id] = [
                    'kode_kriteria' => $kriteria->kode_kriteria,
                    'nilai_awal' => $x,
                    'nilai_utilitas' => $u,
                    'nilai_utilitas_raw' => $uInternal,
                    'nilai_utilitas_format' => number_format($u, 4),
                ];
            }

            $matriksUtilitas[$alternatif->id] = $row;
        }

        return [
            'matriks_utilitas' => $matriksUtilitas,
        ];
    }

    /**
     * Langkah 4: Menghitung Nilai Akhir V_i = sum(w_j * u_ij) & Perankingan
     *
     * @param Collection<int, Alternatif> $alternatifs
     * @param Collection<int, Kriteria> $kriterias
     * @param array<int, array<string, mixed>> $normalizedKriterias
     * @param array<int, array<string, mixed>> $matriksUtilitas
     * @return array<string, mixed>
     */
    public function calculateFinalScoresAndRanking(
        Collection $alternatifs,
        Collection $kriterias,
        array $normalizedKriterias,
        array $matriksUtilitas
    ): array {
        $ranking = [];
        $kontribusiSkor = [];

        foreach ($alternatifs as $alternatif) {
            $totalVi = 0.0;
            $altId = $alternatif->id;
            $kontribusiRow = [
                'alternatif_id' => $altId,
                'kode_alternatif' => $alternatif->kode_alternatif,
                'nama_laptop' => $alternatif->nama_laptop,
                'rincian' => [],
            ];

            foreach ($kriterias as $kriteria) {
                $kritId = $kriteria->id;
                $w = (float) $normalizedKriterias[$kritId]['bobot_normalisasi'];
                $u = (float) $matriksUtilitas[$altId]['utilitas'][$kritId]['nilai_utilitas_raw'];

                $contrib = $w * $u;
                $totalVi += $contrib;

                $kontribusiRow['rincian'][$kritId] = [
                    'kode_kriteria' => $kriteria->kode_kriteria,
                    'bobot_normalisasi' => $w,
                    'utilitas' => $u,
                    'skor_terbobot' => $contrib,
                    'skor_terbobot_format' => number_format($contrib, 4),
                ];
            }

            $kontribusiSkor[$altId] = $kontribusiRow;

            $ranking[] = [
                'alternatif_id' => $altId,
                'kode_alternatif' => $alternatif->kode_alternatif,
                'nama_laptop' => $alternatif->nama_laptop,
                'merek' => $alternatif->merek,
                'spesifikasi_ringkas' => $alternatif->spesifikasi_ringkas,
                'nilai_akhir' => $totalVi,
                'nilai_akhir_raw' => round($totalVi, 6),
                'nilai_akhir_format' => number_format($totalVi, 4),
                'persentase_skor' => round($totalVi * 100, 2),
                'persentase_skor_format' => number_format($totalVi * 100, 2) . '%',
            ];
        }

        // Urutkan nilai_akhir terbesar ke terkecil
        usort($ranking, function ($a, $b) {
            return $b['nilai_akhir'] <=> $a['nilai_akhir'];
        });

        // Tambahkan peringkat 1, 2, 3, dst
        $rank = 1;
        foreach ($ranking as $index => $item) {
            $ranking[$index]['peringkat'] = $rank++;
        }

        return [
            'ranking' => $ranking,
            'kontribusi_skor' => $kontribusiSkor,
        ];
    }
}
