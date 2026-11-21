@extends('layouts.app', ['title' => 'Simulator Bobot Interaktif', 'header' => 'Simulasi Dinamis Bobot Kriteria SMART'])

@section('content')
<div x-data="{
    weights: {
        @foreach($kriterias as $k)
        {{ $k->id }}: {{ $simulatedWeights[$k->id] ?? (float)$k->bobot_awal }},
        @endforeach
    },
    defaultWeights: {
        @foreach($kriterias as $k)
        {{ $k->id }}: {{ (float)$k->bobot_awal }},
        @endforeach
    },
    resetDefaults() {
        for (const [id, val] of Object.entries(this.defaultWeights)) {
            this.weights[id] = val;
        }
    },
    totalWeight() {
        return Object.values(this.weights).reduce((a, b) => parseFloat(a || 0) + parseFloat(b || 0), 0);
    }
}" class="space-y-6">

    <!-- Header Description -->
    <div class="bg-gradient-to-r from-cyan-950 via-slate-900 to-slate-900 border border-cyan-500/20 p-6 rounded-3xl shadow-xl flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full text-xs font-semibold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 mb-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <span>What-If Analysis & Sensitivity</span>
            </div>
            <h2 class="text-2xl font-black text-white">Simulator Bobot Kriteria Interaktif</h2>
            <p class="text-xs text-slate-300 mt-1 max-w-2xl">
                Uji sensitivitas perangkingan tanpa mengubah data di database. Geser slider atau isi nilai bobot untuk melihat bagaimana perubahan prioritas kriteria memengaruhi posisi ranking laptop secara langsung.
            </p>
        </div>

        <div class="flex items-center space-x-3">
            @if($hasil['ringkasan']['is_simulasi'])
            <span class="px-3 py-1.5 rounded-xl bg-amber-500/20 text-amber-300 border border-amber-500/30 text-xs font-semibold">
                ● Mode Simulasi Aktif
            </span>
            <a href="{{ route('perhitungan.simulator') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-all">
                Kembalikan Default
            </a>
            @endif
        </div>
    </div>

    <!-- Simulator Form & Interactive Sliders -->
    <form action="{{ route('perhitungan.simulator') }}" method="GET" class="space-y-6">
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 shadow-2xl">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-5 border-b border-slate-800 gap-3">
                <div>
                    <h3 class="text-base font-bold text-white">Sesuaikan Nilai Bobot Kriteria</h3>
                    <p class="text-xs text-slate-400">Atur bobot relatif tiap aspek penentu laptop 3D & Video Editing.</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-xs text-slate-400">
                        Total Bobot Simulasi: <strong class="text-white font-mono-code" x-text="totalWeight().toFixed(2)"></strong>
                    </div>
                    <button type="button" @click="resetDefaults()" class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold">
                        Reset ke Nilai DB
                    </button>
                </div>
            </div>

            <!-- Sliders Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-6">
                @foreach($kriterias as $k)
                <div class="bg-slate-950/70 border border-slate-800 rounded-2xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="font-mono-code font-bold text-indigo-400 text-xs px-2 py-0.5 rounded bg-slate-800 border border-slate-700">
                                {{ $k->kode_kriteria }}
                            </span>
                            <span class="text-xs font-semibold text-white truncate max-w-[140px]" title="{{ $k->nama_kriteria }}">
                                {{ $k->nama_kriteria }}
                            </span>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $k->sifat === 'benefit' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300' }}">
                            {{ ucfirst($k->sifat) }}
                        </span>
                    </div>

                    <div class="flex items-center space-x-3">
                        <input type="range" min="0" max="100" step="1"
                            x-model.number="weights[{{ $k->id }}]"
                            class="w-full h-1.5 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-indigo-500">
                        <input type="number" step="0.5" min="0" max="100"
                            name="weights[{{ $k->id }}]"
                            x-model.number="weights[{{ $k->id }}]"
                            class="w-16 bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-center font-mono-code text-xs text-white focus:outline-none focus:border-indigo-500">
                    </div>

                    <div class="flex items-center justify-between text-[11px] text-slate-400">
                        <span>Satuan: {{ $k->satuan }}</span>
                        <span>Bobot Asli: {{ (float)$k->bobot_awal }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 pt-5 border-t border-slate-800 flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-cyan-600 hover:from-indigo-500 hover:to-cyan-500 text-white font-semibold text-sm transition-all shadow-lg shadow-indigo-600/30 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Kalkulasi Ulang Simulasi</span>
                </button>
            </div>
        </div>
    </form>

    <!-- Simulated Results Table -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
        <div class="p-5 border-b border-slate-800 bg-slate-950/60 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-white">Hasil Ranking Simulasi Terkini</h3>
                <p class="text-xs text-slate-400">Peringkat alternatif berdasarkan bobot simulasi di atas.</p>
            </div>
            @if($hasil['ringkasan']['is_simulasi'])
            <span class="text-xs text-cyan-400 font-mono-code font-bold">
                ✓ Menggunakan Bobot Simulasi
            </span>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-950/80 border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-6 text-center">Peringkat</th>
                        <th class="py-4 px-6">Kode</th>
                        <th class="py-4 px-6">Nama Laptop</th>
                        <th class="py-4 px-6">Merek</th>
                        <th class="py-4 px-6">Skor Utilitas ($V_i$)</th>
                        <th class="py-4 px-6 text-right">Persentase</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-sm">
                    @foreach($hasil['hasil_akhir'] as $item)
                    <tr class="hover:bg-slate-800/40 {{ $item['peringkat'] === 1 ? 'bg-cyan-500/5' : '' }}">
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl font-bold text-xs
                                {{ $item['peringkat'] === 1 ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40' : 'bg-slate-800 text-slate-400' }}">
                                #{{ $item['peringkat'] }}
                            </span>
                        </td>
                        <td class="py-4 px-6 font-mono-code font-bold text-indigo-400">
                            {{ $item['kode_alternatif'] }}
                        </td>
                        <td class="py-4 px-6 font-semibold text-white">
                            {{ $item['nama_laptop'] }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-2.5 py-1 rounded-lg bg-slate-800 text-xs text-slate-300 border border-slate-700/60">{{ $item['merek'] }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-3">
                                <span class="font-mono-code font-bold text-white text-sm min-w-[50px]">{{ $item['nilai_akhir_format'] }}</span>
                                <div class="w-36 bg-slate-800 rounded-full h-2 overflow-hidden">
                                    <div class="h-2 rounded-full {{ $item['peringkat'] === 1 ? 'bg-cyan-400' : 'bg-indigo-500' }}" style="width: {{ $item['persentase_skor'] }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-right font-mono-code font-bold {{ $item['peringkat'] === 1 ? 'text-cyan-400' : 'text-slate-300' }}">
                            {{ $item['persentase_skor_format'] }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
