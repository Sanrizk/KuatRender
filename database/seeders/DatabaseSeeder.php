<?php

namespace Database\Seeders;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. User default admin
        User::updateOrCreate(
            ['email' => 'admin@kuatrender.test'],
            [
                'name' => 'Admin KuatRender',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 2. Kriteria
        $kriteriasData = [
            [
                'kode_kriteria' => 'C1',
                'nama_kriteria' => 'GPU (Kapasitas VRAM)',
                'satuan' => 'GB',
                'sifat' => 'benefit',
                'bobot_awal' => 25.00,
            ],
            [
                'kode_kriteria' => 'C2',
                'nama_kriteria' => 'Processor (Max Boost Clock)',
                'satuan' => 'GHz',
                'sifat' => 'benefit',
                'bobot_awal' => 20.00,
            ],
            [
                'kode_kriteria' => 'C3',
                'nama_kriteria' => 'Processor (Jumlah Core)',
                'satuan' => 'Core',
                'sifat' => 'benefit',
                'bobot_awal' => 15.00,
            ],
            [
                'kode_kriteria' => 'C4',
                'nama_kriteria' => 'RAM (Kapasitas Memori)',
                'satuan' => 'GB',
                'sifat' => 'benefit',
                'bobot_awal' => 15.00,
            ],
            [
                'kode_kriteria' => 'C5',
                'nama_kriteria' => 'SSD (Kapasitas Penyimpanan)',
                'satuan' => 'GB',
                'sifat' => 'benefit',
                'bobot_awal' => 10.00,
            ],
            [
                'kode_kriteria' => 'C6',
                'nama_kriteria' => 'Daya Adaptor (Power Supply)',
                'satuan' => 'Watt',
                'sifat' => 'cost',
                'bobot_awal' => 15.00,
            ],
        ];

        $kriteriaMap = [];
        foreach ($kriteriasData as $item) {
            $kriteria = Kriteria::updateOrCreate(
                ['kode_kriteria' => $item['kode_kriteria']],
                $item
            );
            $kriteriaMap[$item['kode_kriteria']] = $kriteria->id;
        }

        // 3. Alternatif Laptop
        $alternatifsData = [
            [
                'kode_alternatif' => 'A1',
                'nama_laptop' => 'ASUS ProArt Studiobook 16',
                'merek' => 'ASUS',
                'spesifikasi_ringkas' => 'Laptop workstation kreator dengan layar OLED 16 inci dan dial kontrol presisi.',
            ],
            [
                'kode_alternatif' => 'A2',
                'nama_laptop' => 'Lenovo Legion Slim 5',
                'merek' => 'Lenovo',
                'spesifikasi_ringkas' => 'Laptop tipis bertenaga tinggi untuk rendering dan gaming hybrid.',
            ],
            [
                'kode_alternatif' => 'A3',
                'nama_laptop' => 'Acer Predator Helios 16',
                'merek' => 'Acer',
                'spesifikasi_ringkas' => 'Laptop performa tinggi dengan pendingin canggih untuk simulasi 3D berat.',
            ],
            [
                'kode_alternatif' => 'A4',
                'nama_laptop' => 'MSI Creator Z16',
                'merek' => 'MSI',
                'spesifikasi_ringkas' => 'Laptop premium dengan rasio layar emas 16:10 dan bodi unibody CNC.',
            ],
        ];

        $alternatifMap = [];
        foreach ($alternatifsData as $item) {
            $alternatif = Alternatif::updateOrCreate(
                ['kode_alternatif' => $item['kode_alternatif']],
                $item
            );
            $alternatifMap[$item['kode_alternatif']] = $alternatif->id;
        }

        // 4. Matriks Nilai Penilaian
        // A1: C1: 8, C2: 5.6, C3: 24, C4: 32, C5: 1000, C6: 240
        // A2: C1: 8, C2: 5.1, C3: 8,  C4: 16, C5: 512,  C6: 170
        // A3: C1: 12, C2: 5.4, C3: 16, C4: 16, C5: 1000, C6: 330
        // A4: C1: 8, C2: 5.0, C3: 14, C4: 32, C5: 2000, C6: 240
        $penilaiansData = [
            'A1' => ['C1' => 8, 'C2' => 5.6, 'C3' => 24, 'C4' => 32, 'C5' => 1000, 'C6' => 240],
            'A2' => ['C1' => 8, 'C2' => 5.1, 'C3' => 8,  'C4' => 16, 'C5' => 512,  'C6' => 170],
            'A3' => ['C1' => 12, 'C2' => 5.4, 'C3' => 16, 'C4' => 16, 'C5' => 1000, 'C6' => 330],
            'A4' => ['C1' => 8, 'C2' => 5.0, 'C3' => 14, 'C4' => 32, 'C5' => 2000, 'C6' => 240],
        ];

        foreach ($penilaiansData as $kodeAlt => $kriteriaValues) {
            $altId = $alternatifMap[$kodeAlt];
            foreach ($kriteriaValues as $kodeKrit => $nilai) {
                $kritId = $kriteriaMap[$kodeKrit];
                Penilaian::updateOrCreate(
                    [
                        'alternatif_id' => $altId,
                        'kriteria_id' => $kritId,
                    ],
                    [
                        'nilai' => $nilai,
                    ]
                );
            }
        }
    }
}
