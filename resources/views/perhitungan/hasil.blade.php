@extends('layouts.app', ['title' => 'Detail Perhitungan SMART', 'header' => 'Laporan Lengkap & Transparansi Perhitungan SMART'])

@section('content')
<style>
    @media print {
        body {
            background-color: #ffffff !important;
            color: #0f172a !important;
        }
        aside, header, nav, .no-print, button {
            display: none !important;
        }
        main {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }
        .print-container {
            padding: 20px !important;
            color: #0f172a !important;
        }
        .print-black-text {
            color: #0f172a !important;
        }
        .print-border {
            border: 1px solid #cbd5e1 !important;
        }
        .print-bg-header {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
        }
        .print-badge {
            border: 1px solid #94a3b8 !important;
            color: #0f172a !important;
            background: none !important;
        }
        .print-page-break {
            page-break-before: always;
        }
    }
</style>

<div class="space-y-8 print-container">

    <!-- KOP LAPORAN UNTUK CETAK / PRINT -->
    <div class="hidden print:block border-b-2 border-slate-900 pb-4 mb-6">
        <div class="text-center">
            <h1 class="text-2xl font-black tracking-tight text-slate-900 uppercase">KuatRender — Sistem Pendukung Keputusan</h1>
            <h2 class="text-base font-semibold text-slate-700 mt-1">Laporan Hasil Keputusan Metode SMART (Simple Multi-Attribute Rating Technique)</h2>
            <p class="text-xs text-slate-500 mt-1">Pemilihan Laptop Performa Tinggi untuk Kebutuhan Desain 3D & Video Editing</p>
            <p class="text-[10px] text-slate-400 mt-0.5">Dicetak pada: {{ now()->translatedFormat('l, d F Y H:i:s') }}</p>
        </div>
    </div>

    <!-- Top Action & Info Bar (Screen Only) -->
    <div class="no-print bg-gradient-to-r from-indigo-950 via-slate-900 to-slate-900 border border-indigo-500/20 p-6 rounded-3xl shadow-2xl flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 mb-2">
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                <span>Transparansi Laporan Ilmiah SMART</span>
            </div>
            <h2 class="text-2xl font-black text-white">Detail Perhitungan Algoritma SMART</h2>
            <p class="text-xs text-slate-300 mt-1 max-w-2xl">
                Laporan ilmiah berurutan mencakup 4 tahapan matematis lengkap: Normalisasi Bobot, Matriks Keputusan & Min-Max, Matriks Utilitas, serta Hasil Akhir Rekomendasi.
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs transition-all shadow-lg shadow-indigo-600/30 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Export PDF / Cetak Laporan</span>
            </button>
            <a href="{{ route('perhitungan.simulator') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-medium text-xs transition-all flex items-center space-x-2">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                <span>Simulator Bobot</span>
            </a>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAHAP 1: NORMALISASI BOBOT KRITERIA        -->
    <!-- ========================================== -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-3xl overflow-hidden shadow-xl print-border print:bg-white print:rounded-none">
        <div class="p-5 border-b border-slate-800 bg-slate-950/60 print-bg-header print-border">
            <div class="flex items-center space-x-3">
                <span class="w-7 h-7 rounded-lg bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 font-bold text-xs flex items-center justify-center print-badge">1</span>
                <div>
                    <h3 class="text-base font-bold text-white print-black-text">Tahap 1: Normalisasi Bobot Kriteria ($w_j$)</h3>
                    <p class="text-xs text-slate-400 print-black-text">Rumus: $w_j = \frac{bobot\_awal_j}{\sum bobot\_awal}$</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-950/80 border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider print-bg-header print-black-text">
                        <th class="py-3.5 px-6">Kode</th>
                        <th class="py-3.5 px-6">Nama Kriteria</th>
                        <th class="py-3.5 px-6">Satuan</th>
                        <th class="py-3.5 px-6">Sifat</th>
                        <th class="py-3.5 px-6 text-right">Bobot Awal</th>
                        <th class="py-3.5 px-6 text-right">Bobot Normalisasi ($w_j$)</th>
                        <th class="py-3.5 px-6 text-right">Persentase</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 print:divide-slate-200">
                    @foreach($hasil['kriterias'] as $k)
                    <tr class="hover:bg-slate-800/40 print-black-text">
                        <td class="py-3.5 px-6 font-mono-code font-bold text-indigo-400 print-black-text">{{ $k['kode_kriteria'] }}</td>
                        <td class="py-3.5 px-6 text-white font-medium print-black-text">{{ $k['nama_kriteria'] }}</td>
                        <td class="py-3.5 px-6 font-mono-code text-xs text-slate-300 print-black-text">{{ $k['satuan'] }}</td>
                        <td class="py-3.5 px-6">
                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $k['sifat'] === 'benefit' ? 'bg-emerald-500/20 text-emerald-300 print-badge' : 'bg-amber-500/20 text-amber-300 print-badge' }}">
                                {{ ucfirst($k['sifat']) }}
                            </span>
                        </td>
                        <td class="py-3.5 px-6 text-right font-mono-code text-white print-black-text">{{ $k['bobot_awal_format'] }}</td>
                        <td class="py-3.5 px-6 text-right font-mono-code font-bold text-cyan-400 print-black-text">{{ $k['bobot_normalisasi_format'] }}</td>
                        <td class="py-3.5 px-6 text-right font-mono-code text-slate-400 print-black-text">{{ $k['persentase_bobot'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-slate-950/90 border-t-2 border-slate-800 font-bold text-xs text-slate-300 print-bg-header print-black-text">
                        <td colspan="4" class="py-3 px-6 text-right uppercase tracking-wider text-slate-400 print-black-text">Total Keseluruhan:</td>
                        <td class="py-3 px-6 text-right font-mono-code text-white print-black-text">{{ number_format($hasil['ringkasan']['total_bobot_awal'], 2) }}</td>
                        <td class="py-3 px-6 text-right font-mono-code text-cyan-400 print-black-text">1.0000</td>
                        <td class="py-3 px-6 text-right font-mono-code text-slate-400 print-black-text">100.00%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAHAP 2: MATRIKS KEPUTUSAN & MIN-MAX      -->
    <!-- ========================================== -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-3xl overflow-hidden shadow-xl print-border print:bg-white print:rounded-none">
        <div class="p-5 border-b border-slate-800 bg-slate-950/60 print-bg-header print-border">
            <div class="flex items-center space-x-3">
                <span class="w-7 h-7 rounded-lg bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 font-bold text-xs flex items-center justify-center print-badge">2</span>
                <div>
                    <h3 class="text-base font-bold text-white print-black-text">Tahap 2: Matriks Keputusan Awal ($X$) & Nilai Ekstrem ($C_{min}, C_{max}$)</h3>
                    <p class="text-xs text-slate-400 print-black-text">Nilai asli spesifikasi laptop pada setiap kriteria beserta batas minimum dan maksimum seluruh alternatif.</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-950/80 border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider print-bg-header print-black-text">
                        <th class="py-3.5 px-6">Alternatif Laptop</th>
                        @foreach($hasil['kriterias'] as $k)
                        <th class="py-3.5 px-4 text-center">
                            <div class="font-mono-code text-indigo-400 print-black-text">{{ $k['kode_kriteria'] }}</div>
                            <div class="text-[10px] text-slate-400 print-black-text font-normal">({{ $k['satuan'] }})</div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 print:divide-slate-200">
                    @foreach($hasil['matriks_keputusan'] as $row)
                    <tr class="hover:bg-slate-800/40 print-black-text">
                        <td class="py-3.5 px-6 font-semibold text-white print-black-text">
                            <span class="font-mono-code text-indigo-400 font-bold mr-2 print-black-text">{{ $row['kode_alternatif'] }}</span>
                            <span>{{ $row['nama_laptop'] }}</span>
                            <span class="text-xs text-slate-400 print-black-text font-normal">({{ $row['merek'] }})</span>
                        </td>
                        @foreach($hasil['kriterias'] as $kId => $k)
                        <td class="py-3.5 px-4 text-center font-mono-code text-slate-200 print-black-text">
                            {{ $row['nilai_kriteria'][$kId]['nilai_format'] }}
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-slate-950/90 border-t-2 border-slate-800 font-bold text-xs text-emerald-400 print-bg-header print-black-text">
                        <td class="py-3 px-6 text-right uppercase tracking-wider text-slate-300 print-black-text">Nilai Maksimum ($C_{max}$):</td>
                        @foreach($hasil['kriterias'] as $kId => $k)
                        <td class="py-3 px-4 text-center font-mono-code print-black-text">
                            {{ $hasil['nilai_ekstrem'][$kId]['c_max_format'] }}
                        </td>
                        @endforeach
                    </tr>
                    <tr class="bg-slate-950/90 border-t border-slate-800 font-bold text-xs text-amber-400 print-bg-header print-black-text">
                        <td class="py-3 px-6 text-right uppercase tracking-wider text-slate-300 print-black-text">Nilai Minimum ($C_{min}$):</td>
                        @foreach($hasil['kriterias'] as $kId => $k)
                        <td class="py-3 px-4 text-center font-mono-code print-black-text">
                            {{ $hasil['nilai_ekstrem'][$kId]['c_min_format'] }}
                        </td>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAHAP 3: MATRIKS NILAI UTILITAS            -->
    <!-- ========================================== -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-3xl overflow-hidden shadow-xl print-border print:bg-white print:rounded-none">
        <div class="p-5 border-b border-slate-800 bg-slate-950/60 print-bg-header print-border">
            <div class="flex items-center space-x-3">
                <span class="w-7 h-7 rounded-lg bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 font-bold text-xs flex items-center justify-center print-badge">3</span>
                <div>
                    <h3 class="text-base font-bold text-white print-black-text">Tahap 3: Matriks Nilai Utilitas ($u_{ij}$)</h3>
                    <p class="text-xs text-slate-400 print-black-text">
                        Benefit: $u_{ij} = \frac{x_{ij} - C_{min}}{C_{max} - C_{min}}$ | Cost: $u_{ij} = \frac{C_{max} - x_{ij}}{C_{max} - C_{min}}$ (Presisi 4 desimal).
                    </p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-950/80 border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider print-bg-header print-black-text">
                        <th class="py-3.5 px-6">Alternatif Laptop</th>
                        @foreach($hasil['kriterias'] as $k)
                        <th class="py-3.5 px-4 text-center">
                            <div class="font-mono-code text-indigo-400 print-black-text">{{ $k['kode_kriteria'] }}</div>
                            <div class="text-[10px] text-slate-400 print-black-text font-normal">({{ ucfirst($k['sifat']) }})</div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 print:divide-slate-200">
                    @foreach($hasil['matriks_utilitas'] as $row)
                    <tr class="hover:bg-slate-800/40 print-black-text">
                        <td class="py-3.5 px-6 font-semibold text-white print-black-text">
                            <span class="font-mono-code text-indigo-400 font-bold mr-2 print-black-text">{{ $row['kode_alternatif'] }}</span>
                            <span>{{ $row['nama_laptop'] }}</span>
                        </td>
                        @foreach($hasil['kriterias'] as $kId => $k)
                        @php
                            $val = $row['utilitas'][$kId]['nilai_utilitas'];
                        @endphp
                        <td class="py-3.5 px-4 text-center font-mono-code print-black-text">
                            <span class="{{ $val == 1.0 ? 'text-emerald-400 font-bold' : ($val == 0.0 ? 'text-slate-500' : 'text-slate-200') }} print-black-text">
                                {{ $row['utilitas'][$kId]['nilai_utilitas_format'] }}
                            </span>
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAHAP 4: PERANKINGAN & KARTU KESIMPULAN    -->
    <!-- ========================================== -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-3xl overflow-hidden shadow-xl print-border print:bg-white print:rounded-none">
        <div class="p-5 border-b border-slate-800 bg-slate-950/60 print-bg-header print-border">
            <div class="flex items-center space-x-3">
                <span class="w-7 h-7 rounded-lg bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 font-bold text-xs flex items-center justify-center print-badge">4</span>
                <div>
                    <h3 class="text-base font-bold text-white print-black-text">Tahap 4: Tabel Hasil Akhir & Perankingan ($V_i$)</h3>
                    <p class="text-xs text-slate-400 print-black-text">Rumus nilai akhir: $V_i = \sum_{j=1}^m (w_j \times u_{ij})$</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-950/80 border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider print-bg-header print-black-text">
                        <th class="py-3.5 px-6 text-center">Peringkat</th>
                        <th class="py-3.5 px-6">Kode</th>
                        <th class="py-3.5 px-6">Nama Model Laptop</th>
                        <th class="py-3.5 px-6">Merek</th>
                        <th class="py-3.5 px-6 text-right">Nilai Akhir ($V_i$)</th>
                        <th class="py-3.5 px-6 text-right">Persentase Kelayakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 print:divide-slate-200">
                    @foreach($hasil['hasil_akhir'] as $item)
                    <tr class="hover:bg-slate-800/40 print-black-text {{ $item['peringkat'] === 1 ? 'bg-amber-500/5' : '' }}">
                        <td class="py-3.5 px-6 text-center">
                            @if($item['peringkat'] === 1)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-amber-500/20 text-amber-300 border border-amber-500/40 shadow-sm print-badge">
                                🥇 Peringkat 1 (Emas)
                            </span>
                            @elseif($item['peringkat'] === 2)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-300/20 text-slate-200 border border-slate-400/40 print-badge">
                                🥈 Peringkat 2 (Perak)
                            </span>
                            @elseif($item['peringkat'] === 3)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-700/20 text-amber-400 border border-amber-600/40 print-badge">
                                🥉 Peringkat 3 (Perunggu)
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-800 text-slate-400 print-badge">
                                Peringkat {{ $item['peringkat'] }}
                            </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-6 font-mono-code font-bold text-indigo-400 print-black-text">
                            {{ $item['kode_alternatif'] }}
                        </td>
                        <td class="py-3.5 px-6 font-semibold text-white print-black-text">
                            {{ $item['nama_laptop'] }}
                        </td>
                        <td class="py-3.5 px-6 text-slate-300 print-black-text">
                            <span class="px-2 py-0.5 rounded bg-slate-800 text-xs border border-slate-700/60 print-badge">{{ $item['merek'] }}</span>
                        </td>
                        <td class="py-3.5 px-6 text-right font-mono-code font-bold text-base {{ $item['peringkat'] === 1 ? 'text-amber-400' : 'text-white' }} print-black-text">
                            {{ $item['nilai_akhir_format'] }}
                        </td>
                        <td class="py-3.5 px-6 text-right font-mono-code font-semibold {{ $item['peringkat'] === 1 ? 'text-amber-400' : 'text-slate-300' }} print-black-text">
                            {{ $item['persentase_skor_format'] }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- KARTU KESIMPULAN OTOMATIS -->
    @php
        $best = $hasil['hasil_akhir'][0] ?? null;
        $runnerUp = $hasil['hasil_akhir'][1] ?? null;
    @endphp
    @if($best)
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-950 via-slate-900 to-amber-950/40 border-2 border-amber-500/40 p-7 shadow-2xl print-border print:bg-white print:rounded-none">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded-full bg-amber-400 animate-ping"></span>
                    <span class="text-xs uppercase tracking-wider font-extrabold text-amber-400 print-black-text">
                        Kesimpulan & Rekomendasi Utama KuatRender
                    </span>
                </div>
                <h3 class="text-2xl font-black text-white print-black-text">
                    {{ $best['nama_laptop'] }} ({{ $best['kode_alternatif'] }})
                </h3>
                <p class="text-sm text-slate-300 leading-relaxed max-w-3xl print-black-text">
                    Berdasarkan perhitungan metode <strong>SMART</strong> dari seluruh parameter teknis (VRAM GPU, clock CPU, core processor, RAM, kapasitas SSD, dan konsumsi daya adaptor), laptop <strong>{{ $best['nama_laptop'] }}</strong> terpilih sebagai rekomendasi terbaik dengan perolehan nilai utilitas tertinggi sebesar <strong class="text-amber-400 font-mono-code print-black-text">{{ $best['nilai_akhir_format'] }}</strong> (Tingkat Kelayakan {{ $best['persentase_skor_format'] }}).
                    @if($runnerUp)
                    Laptop ini mengungguli alternatif terdekatnya, yaitu <strong>{{ $runnerUp['nama_laptop'] }}</strong> dengan skor {{ $runnerUp['nilai_akhir_format'] }}.
                    @endif
                </p>
            </div>

            <div class="shrink-0 text-center bg-slate-950/80 border border-amber-500/30 rounded-2xl p-5 min-w-[180px] print-border print:bg-white">
                <span class="text-[10px] uppercase font-bold text-slate-400 print-black-text tracking-wider">Skor Utilitas ($V_i$)</span>
                <div class="text-4xl font-black text-amber-400 font-mono-code my-1 print-black-text">
                    {{ $best['nilai_akhir_format'] }}
                </div>
                <span class="text-xs font-semibold text-emerald-400 print-black-text">
                    {{ $best['persentase_skor_format'] }} Kelayakan
                </span>
            </div>
        </div>
    </div>
    @endif

    <!-- Tanda Tangan Laporan (Khusus Print) -->
    <div class="hidden print:block pt-8 mt-10 border-t border-slate-300">
        <div class="flex justify-between text-xs text-slate-700">
            <div>
                <p>Mengetahui,</p>
                <p class="font-bold mt-16">Pengelola Sistem KuatRender</p>
            </div>
            <div class="text-right">
                <p>Dicetak secara sistematis oleh:</p>
                <p class="font-bold mt-16">KuatRender SPK Decision Engine</p>
            </div>
        </div>
    </div>

</div>
@endsection
