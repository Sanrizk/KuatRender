@extends('layouts.app', ['title' => 'Hasil Perhitungan SMART', 'header' => 'Laporan Lengkap & Tahapan Perhitungan SMART'])

@section('content')
<div x-data="{ activeTab: 'ranking' }" class="space-y-6">

    <!-- Top Summary Banner -->
    <div class="bg-gradient-to-r from-indigo-950 via-slate-900 to-slate-900 border border-indigo-500/20 p-6 rounded-3xl shadow-2xl flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 mb-2">
                <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                <span>Simple Multi-Attribute Rating Technique (SMART)</span>
            </div>
            <h2 class="text-2xl font-black text-white">Hasil Rekomendasi Laptop Terbaik</h2>
            <p class="text-xs text-slate-300 mt-1 max-w-xl">
                Dihitung dari matriks spesifikasi laptop dengan pembobotan normalisasi, nilai ekstrem (Min & Max), dan matriks utilitas multi-atribut.
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('perhitungan.simulator') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-xs transition-all shadow-lg shadow-indigo-600/30 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                <span>Uji Simulasi Bobot</span>
            </a>
            @auth
            <a href="{{ route('penilaian.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-medium text-xs transition-all flex items-center space-x-2">
                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span>Ubah Spesifikasi Matriks</span>
            </a>
            @endauth
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex space-x-2 border-b border-slate-800 pb-3 overflow-x-auto">
        <button @click="activeTab = 'ranking'" :class="activeTab === 'ranking' ? 'bg-indigo-600 text-white' : 'bg-slate-900 text-slate-400 hover:text-white'" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all flex items-center space-x-2 shrink-0">
            <span>🏆 Peringkat & Rekomendasi</span>
        </button>
        <button @click="activeTab = 'bobot'" :class="activeTab === 'bobot' ? 'bg-indigo-600 text-white' : 'bg-slate-900 text-slate-400 hover:text-white'" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all flex items-center space-x-2 shrink-0">
            <span>1. Normalisasi Bobot ($w_j$)</span>
        </button>
        <button @click="activeTab = 'matriks'" :class="activeTab === 'matriks' ? 'bg-indigo-600 text-white' : 'bg-slate-900 text-slate-400 hover:text-white'" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all flex items-center space-x-2 shrink-0">
            <span>2. Matriks Keputusan & Min-Max</span>
        </button>
        <button @click="activeTab = 'utilitas'" :class="activeTab === 'utilitas' ? 'bg-indigo-600 text-white' : 'bg-slate-900 text-slate-400 hover:text-white'" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all flex items-center space-x-2 shrink-0">
            <span>3. Matriks Utilitas ($u_{ij}$)</span>
        </button>
        <button @click="activeTab = 'kontribusi'" :class="activeTab === 'kontribusi' ? 'bg-indigo-600 text-white' : 'bg-slate-900 text-slate-400 hover:text-white'" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all flex items-center space-x-2 shrink-0">
            <span>4. Rincian Terbobot ($w_j \times u_{ij}$)</span>
        </button>
    </div>

    <!-- TAB 1: Ranking & Rekomendasi -->
    <div x-show="activeTab === 'ranking'" x-transition class="space-y-6">
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
            <div class="p-5 border-b border-slate-800 bg-slate-950/60 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-white">Tabel Peringkat Akhir (Nilai $V_i$)</h3>
                    <p class="text-xs text-slate-400">Urutan rekomendasi laptop dari nilai utilitas terbesar hingga terkecil.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-950/80 border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-4 px-6 text-center">Peringkat</th>
                            <th class="py-4 px-6">Kode</th>
                            <th class="py-4 px-6">Nama Laptop</th>
                            <th class="py-4 px-6">Merek</th>
                            <th class="py-4 px-6">Progres Kelayakan</th>
                            <th class="py-4 px-6 text-right">Nilai Akhir ($V_i$)</th>
                            <th class="py-4 px-6 text-right">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80 text-sm">
                        @foreach($hasil['hasil_akhir'] as $item)
                        <tr class="hover:bg-slate-800/40 transition-colors {{ $item['peringkat'] === 1 ? 'bg-amber-500/5' : '' }}">
                            <td class="py-4 px-6 text-center">
                                @if($item['peringkat'] === 1)
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-amber-500/20 text-amber-300 border border-amber-500/40 font-bold text-sm shadow-lg shadow-amber-500/10">
                                    🥇
                                </span>
                                @elseif($item['peringkat'] === 2)
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-700/50 text-slate-200 border border-slate-600 font-bold text-sm">
                                    🥈
                                </span>
                                @elseif($item['peringkat'] === 3)
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-orange-500/20 text-orange-300 border border-orange-500/40 font-bold text-sm">
                                    🥉
                                </span>
                                @else
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-800 text-slate-400 font-bold text-xs">
                                    #{{ $item['peringkat'] }}
                                </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 font-mono-code font-bold text-indigo-400">
                                {{ $item['kode_alternatif'] }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-white">{{ $item['nama_laptop'] }}</div>
                                <div class="text-xs text-slate-400">{{ $item['spesifikasi_ringkas'] ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-800 text-xs text-slate-300 font-medium border border-slate-700/60">{{ $item['merek'] }}</span>
                            </td>
                            <td class="py-4 px-6 min-w-[180px]">
                                <div class="w-full bg-slate-800 rounded-full h-2.5 overflow-hidden">
                                    <div class="h-2.5 rounded-full {{ $item['peringkat'] === 1 ? 'bg-amber-400' : 'bg-indigo-500' }}" style="width: {{ $item['persentase_skor'] }}%"></div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right font-mono-code font-bold {{ $item['peringkat'] === 1 ? 'text-amber-400 text-base' : 'text-white' }}">
                                {{ $item['nilai_akhir_format'] }}
                            </td>
                            <td class="py-4 px-6 text-right font-mono-code font-semibold {{ $item['peringkat'] === 1 ? 'text-amber-400' : 'text-slate-300' }}">
                                {{ $item['persentase_skor_format'] }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 2: Normalisasi Bobot -->
    <div x-show="activeTab === 'bobot'" x-cloak class="space-y-6">
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
            <div class="p-5 border-b border-slate-800 bg-slate-950/60">
                <h3 class="text-base font-bold text-white">Langkah 1: Normalisasi Bobot Kriteria ($w_j = \frac{bobot_j}{\sum bobot}$)</h3>
                <p class="text-xs text-slate-400 mt-1">Total Bobot Awal: <strong class="text-white">{{ $hasil['ringkasan']['total_bobot_awal'] }}</strong>. Total Bobot Normalisasi: <strong class="text-cyan-400">{{ $hasil['ringkasan']['total_bobot_normalisasi'] }} (100%)</strong>.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-950/80 border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-4 px-6">Kode</th>
                            <th class="py-4 px-6">Nama Kriteria</th>
                            <th class="py-4 px-6">Sifat</th>
                            <th class="py-4 px-6 text-right">Bobot Awal</th>
                            <th class="py-4 px-6 text-right">Bobot Normalisasi ($w_j$)</th>
                            <th class="py-4 px-6 text-right">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80 text-sm">
                        @foreach($hasil['kriterias'] as $k)
                        <tr class="hover:bg-slate-800/40">
                            <td class="py-4 px-6 font-mono-code font-bold text-indigo-400">{{ $k['kode_kriteria'] }}</td>
                            <td class="py-4 px-6 text-white font-medium">{{ $k['nama_kriteria'] }} ({{ $k['satuan'] }})</td>
                            <td class="py-4 px-6">
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $k['sifat'] === 'benefit' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300' }}">
                                    {{ ucfirst($k['sifat']) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right font-mono-code text-white">{{ $k['bobot_awal_format'] }}</td>
                            <td class="py-4 px-6 text-right font-mono-code font-bold text-cyan-400">{{ $k['bobot_normalisasi_format'] }}</td>
                            <td class="py-4 px-6 text-right font-mono-code text-slate-400">{{ $k['persentase_bobot'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 3: Matriks Keputusan & Min-Max -->
    <div x-show="activeTab === 'matriks'" x-cloak class="space-y-6">
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
            <div class="p-5 border-b border-slate-800 bg-slate-950/60">
                <h3 class="text-base font-bold text-white">Langkah 2: Matriks Keputusan ($X$) & Nilai Ekstrem ($C_{min}, C_{max}$)</h3>
                <p class="text-xs text-slate-400 mt-1">Data spesifikasi tiap alternatif beserta batas minimum dan maksimum untuk rumus utilitas.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-950/80 border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-4 px-6 sticky left-0 bg-slate-950/95 z-10 border-r border-slate-800">Alternatif</th>
                            @foreach($hasil['kriterias'] as $k)
                            <th class="py-4 px-4 text-center">
                                <div class="font-mono-code text-indigo-400">{{ $k['kode_kriteria'] }}</div>
                                <div class="text-[10px] text-slate-400">{{ $k['satuan'] }}</div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80">
                        @foreach($hasil['matriks_keputusan'] as $row)
                        <tr class="hover:bg-slate-800/40">
                            <td class="py-4 px-6 font-semibold text-white sticky left-0 bg-slate-900/95 z-10 border-r border-slate-800">
                                <span class="font-mono-code text-indigo-400 font-bold mr-2">{{ $row['kode_alternatif'] }}</span>
                                <span>{{ $row['nama_laptop'] }}</span>
                            </td>
                            @foreach($hasil['kriterias'] as $kId => $k)
                            <td class="py-4 px-4 text-center font-mono-code text-slate-200">
                                {{ $row['nilai_kriteria'][$kId]['nilai_format'] }}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-950/90 border-t-2 border-slate-800 font-bold text-xs text-emerald-400">
                            <td class="py-3 px-6 text-right uppercase tracking-wider sticky left-0 bg-slate-950/95 z-10 border-r border-slate-800">Nilai Max ($C_{max}$):</td>
                            @foreach($hasil['kriterias'] as $kId => $k)
                            <td class="py-3 px-4 text-center font-mono-code">
                                {{ $hasil['nilai_ekstrem'][$kId]['c_max_format'] }}
                            </td>
                            @endforeach
                        </tr>
                        <tr class="bg-slate-950/90 border-t border-slate-800 font-bold text-xs text-amber-400">
                            <td class="py-3 px-6 text-right uppercase tracking-wider sticky left-0 bg-slate-950/95 z-10 border-r border-slate-800">Nilai Min ($C_{min}$):</td>
                            @foreach($hasil['kriterias'] as $kId => $k)
                            <td class="py-3 px-4 text-center font-mono-code">
                                {{ $hasil['nilai_ekstrem'][$kId]['c_min_format'] }}
                            </td>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 4: Matriks Utilitas -->
    <div x-show="activeTab === 'utilitas'" x-cloak class="space-y-6">
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
            <div class="p-5 border-b border-slate-800 bg-slate-950/60">
                <h3 class="text-base font-bold text-white">Langkah 3: Matriks Nilai Utilitas ($u_{ij}$)</h3>
                <p class="text-xs text-slate-400 mt-1">
                    Benefit: $u_{ij} = \frac{x_{ij} - C_{min}}{C_{max} - C_{min}}$ | Cost: $u_{ij} = \frac{C_{max} - x_{ij}}{C_{max} - C_{min}}$ (Skala 0 s/d 1).
                </p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-950/80 border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-4 px-6 sticky left-0 bg-slate-950/95 z-10 border-r border-slate-800">Alternatif</th>
                            @foreach($hasil['kriterias'] as $k)
                            <th class="py-4 px-4 text-center">
                                <div class="font-mono-code text-indigo-400">{{ $k['kode_kriteria'] }}</div>
                                <div class="text-[10px] text-slate-400">({{ ucfirst($k['sifat']) }})</div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80">
                        @foreach($hasil['matriks_utilitas'] as $row)
                        <tr class="hover:bg-slate-800/40">
                            <td class="py-4 px-6 font-semibold text-white sticky left-0 bg-slate-900/95 z-10 border-r border-slate-800">
                                <span class="font-mono-code text-indigo-400 font-bold mr-2">{{ $row['kode_alternatif'] }}</span>
                                <span>{{ $row['nama_laptop'] }}</span>
                            </td>
                            @foreach($hasil['kriterias'] as $kId => $k)
                            <td class="py-4 px-4 text-center font-mono-code">
                                <span class="{{ $row['utilitas'][$kId]['nilai_utilitas'] == 1.0 ? 'text-emerald-400 font-bold' : ($row['utilitas'][$kId]['nilai_utilitas'] == 0.0 ? 'text-slate-500' : 'text-white') }}">
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
    </div>

    <!-- TAB 5: Kontribusi Terbobot -->
    <div x-show="activeTab === 'kontribusi'" x-cloak class="space-y-6">
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
            <div class="p-5 border-b border-slate-800 bg-slate-950/60">
                <h3 class="text-base font-bold text-white">Langkah 4: Rincian Perkalian Bobot & Utilitas ($w_j \times u_{ij}$)</h3>
                <p class="text-xs text-slate-400 mt-1">Total penjumlahan setiap kolom baris menghasilkan nilai akhir $V_i$.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-950/80 border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-4 px-6 sticky left-0 bg-slate-950/95 z-10 border-r border-slate-800">Alternatif</th>
                            @foreach($hasil['kriterias'] as $k)
                            <th class="py-4 px-4 text-center">
                                <div class="font-mono-code text-indigo-400">{{ $k['kode_kriteria'] }}</div>
                                <div class="text-[10px] text-cyan-400">w={{ $k['bobot_normalisasi_format'] }}</div>
                            </th>
                            @endforeach
                            <th class="py-4 px-6 text-right font-bold text-amber-400">Total $V_i$</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80">
                        @foreach($hasil['hasil_akhir'] as $ranked)
                        @php
                            $row = $hasil['kontribusi_skor'][$ranked['alternatif_id']];
                        @endphp
                        <tr class="hover:bg-slate-800/40 {{ $ranked['peringkat'] === 1 ? 'bg-amber-500/5' : '' }}">
                            <td class="py-4 px-6 font-semibold text-white sticky left-0 bg-slate-900/95 z-10 border-r border-slate-800">
                                <span class="font-mono-code text-indigo-400 font-bold mr-2">{{ $row['kode_alternatif'] }}</span>
                                <span>{{ $row['nama_laptop'] }}</span>
                            </td>
                            @foreach($hasil['kriterias'] as $kId => $k)
                            <td class="py-4 px-4 text-center font-mono-code text-slate-300">
                                {{ $row['rincian'][$kId]['skor_terbobot_format'] }}
                            </td>
                            @endforeach
                            <td class="py-4 px-6 text-right font-mono-code font-bold text-amber-400 text-base">
                                {{ $ranked['nilai_akhir_format'] }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
