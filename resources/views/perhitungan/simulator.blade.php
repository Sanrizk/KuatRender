@extends('layouts.app', ['title' => 'Simulator Bobot Interaktif', 'header' => 'Simulasi Sensitivitas Bobot Kriteria SMART'])

@section('content')
<div x-data="simulatorComponent()" x-init="init()" class="space-y-6">

    <!-- Top Info Banner -->
    <div class="bg-gradient-to-r from-indigo-950 via-slate-900 to-slate-900 border border-indigo-500/20 p-6 rounded-3xl shadow-xl flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full text-xs font-semibold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 mb-2">
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                <span>Analisis Sensitivitas Real-Time (Chart.js + Alpine.js)</span>
            </div>
            <h2 class="text-2xl font-black text-white">Simulator Bobot Kriteria Interaktif</h2>
            <p class="text-xs text-slate-300 mt-1 max-w-2xl">
                Geser slider bobot pada panel kiri untuk melihat perubahan peringkat dan skor utilitas laptop secara *real-time* tanpa memuat ulang halaman.
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <button @click="resetToDefault()" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold text-xs transition-all flex items-center space-x-2">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                <span>Reset ke Bobot Default</span>
            </button>
            <a href="{{ route('perhitungan.index') }}" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs transition-all shadow-lg shadow-indigo-600/30 flex items-center space-x-2">
                <span>Lihat Laporan Lengkap</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </div>

    <!-- Main Grid: Left Panel (Sliders) & Right Panel (Chart.js & Live Ranking) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- ========================================== -->
        <!-- SISI KIRI: PANEL SLIDER BOBOT             -->
        <!-- ========================================== -->
        <div class="lg:col-span-5 bg-slate-900/80 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-5">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <div>
                    <h3 class="text-base font-bold text-white">Panel Slider Bobot Kriteria</h3>
                    <p class="text-xs text-slate-400">Rentang slider bobot: 0 s/d 100</p>
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-slate-400 uppercase tracking-wider block">Total Bobot</span>
                    <span class="text-lg font-black font-mono-code text-indigo-400" x-text="calculateTotalWeight().toFixed(2)"></span>
                </div>
            </div>

            <!-- List Slider Per Kriteria -->
            <div class="space-y-4">
                @foreach($kriterias as $k)
                <div class="bg-slate-950/70 border border-slate-800/90 rounded-2xl p-4 space-y-2.5 hover:border-slate-700 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2 min-w-0">
                            <span class="font-mono-code font-bold text-indigo-400 text-xs px-2 py-0.5 rounded bg-slate-800 border border-slate-700 shrink-0">
                                {{ $k->kode_kriteria }}
                            </span>
                            <span class="text-xs font-semibold text-white truncate" title="{{ $k->nama_kriteria }}">
                                {{ $k->nama_kriteria }}
                            </span>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full shrink-0 {{ $k->sifat === 'benefit' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300' }}">
                            {{ ucfirst($k->sifat) }}
                        </span>
                    </div>

                    <!-- Slider & Numeric Input -->
                    <div class="flex items-center space-x-3 pt-1">
                        <input type="range" min="0" max="100" step="1"
                            x-model.number="weights[{{ $k->id }}]"
                            @input="onSliderChange()"
                            class="w-full h-2 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-indigo-500 focus:outline-none">

                        <div class="relative shrink-0">
                            <input type="number" step="1" min="0" max="100"
                                x-model.number="weights[{{ $k->id }}]"
                                @input="onSliderChange()"
                                class="w-16 bg-slate-900 border border-slate-700 rounded-xl px-2.5 py-1 text-center font-mono-code text-xs text-white focus:outline-none focus:border-indigo-500 font-bold">
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-[11px] text-slate-400 pt-0.5">
                        <span>Satuan: <strong class="text-slate-300">{{ $k->satuan }}</strong></span>
                        <span>Bobot Ternormalisasi: <strong class="text-cyan-400 font-mono-code" x-text="getNormalizedWeight({{ $k->id }}).toFixed(4)"></strong></span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- ========================================== -->
        <!-- SISI KANAN: GRAFIK CHART.JS & PERANKINGAN -->
        <!-- ========================================== -->
        <div class="lg:col-span-7 space-y-6">

            <!-- Chart.js Card -->
            <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                    <div>
                        <h3 class="text-base font-bold text-white flex items-center space-x-2">
                            <span>Visualisasi Skor Utilitas Akhir ($V_i$)</span>
                            <span x-show="loading" class="text-xs text-indigo-400 animate-pulse font-normal">(Menghitung...)</span>
                        </h3>
                        <p class="text-xs text-slate-400">Grafik batang horizontal animasi dinamis dari skor peringkat tertinggi ke terendah.</p>
                    </div>
                    <div class="text-xs text-slate-400 font-mono-code">
                        Skala: 0.0 s/d 1.0
                    </div>
                </div>

                <div class="relative h-72 w-full">
                    <canvas id="rankingChart"></canvas>
                </div>
            </div>

            <!-- Live Ranking Table -->
            <div class="bg-slate-900/80 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
                <div class="p-5 border-b border-slate-800 bg-slate-950/60 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-white">Peringkat Real-Time Alternatif Laptop</h3>
                        <p class="text-xs text-slate-400">Urutan rekomendasi terbarui otomatis sesuai bobot simulasi di samping.</p>
                    </div>
                    <span class="text-xs text-emerald-400 font-mono-code font-bold">
                        ● Live Updated
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-950/80 border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="py-3.5 px-6 text-center">Peringkat</th>
                                <th class="py-3.5 px-6">Kode</th>
                                <th class="py-3.5 px-6">Nama Laptop</th>
                                <th class="py-3.5 px-6">Merek</th>
                                <th class="py-3.5 px-6 text-right">Skor Utilitas ($V_i$)</th>
                                <th class="py-3.5 px-6 text-right">Persentase</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80">
                            <template x-for="(item, index) in rankings" :key="item.alternatif_id">
                                <tr class="hover:bg-slate-800/40 transition-colors" :class="item.peringkat === 1 ? 'bg-amber-500/5' : ''">
                                    <td class="py-3.5 px-6 text-center">
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg font-bold text-xs"
                                            :class="item.peringkat === 1 ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40' : (item.peringkat === 2 ? 'bg-slate-700/50 text-slate-200 border border-slate-600' : (item.peringkat === 3 ? 'bg-orange-500/20 text-orange-300 border border-orange-500/40' : 'bg-slate-800 text-slate-400'))">
                                            <span x-text="item.peringkat === 1 ? '🥇' : (item.peringkat === 2 ? '🥈' : (item.peringkat === 3 ? '🥉' : '#' + item.peringkat))"></span>
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-6 font-mono-code font-bold text-indigo-400" x-text="item.kode_alternatif"></td>
                                    <td class="py-3.5 px-6 font-semibold text-white" x-text="item.nama_laptop"></td>
                                    <td class="py-3.5 px-6">
                                        <span class="px-2 py-0.5 rounded bg-slate-800 text-xs text-slate-300 border border-slate-700/60" x-text="item.merek"></span>
                                    </td>
                                    <td class="py-3.5 px-6 text-right font-mono-code font-bold"
                                        :class="item.peringkat === 1 ? 'text-amber-400 text-base' : 'text-white'"
                                        x-text="item.nilai_akhir_format"></td>
                                    <td class="py-3.5 px-6 text-right font-mono-code font-semibold"
                                        :class="item.peringkat === 1 ? 'text-amber-400' : 'text-slate-300'"
                                        x-text="item.persentase_skor_format"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Rekomendasi Dinamis -->
            <template x-if="rankings.length > 0">
                <div class="bg-gradient-to-r from-slate-900 to-indigo-950/60 border border-indigo-500/30 rounded-2xl p-5 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-indigo-400 tracking-wider">Laptop Terpilih Peringkat 1</span>
                        <h4 class="text-lg font-bold text-white mt-0.5" x-text="rankings[0].nama_laptop + ' (' + rankings[0].kode_alternatif + ')'"></h4>
                        <p class="text-xs text-slate-400 mt-1">
                            Memperoleh nilai tertinggi dengan skor <strong class="text-amber-400 font-mono-code" x-text="rankings[0].nilai_akhir_format"></strong> pada kombinasi bobot kriteria saat ini.
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-black font-mono-code text-amber-400" x-text="rankings[0].persentase_skor_format"></span>
                    </div>
                </div>
            </template>

        </div>
    </div>

</div>

@push('scripts')
<script>
function simulatorComponent() {
    return {
        weights: {
            @foreach($kriterias as $k)
            {{ $k->id }}: {{ (float)$k->bobot_awal }},
            @endforeach
        },
        defaultWeights: {
            @foreach($kriterias as $k)
            {{ $k->id }}: {{ (float)$k->bobot_awal }},
            @endforeach
        },
        rankings: @json($hasil['hasil_akhir']),
        chart: null,
        loading: false,
        debounceTimer: null,

        init() {
            this.initChart();
        },

        calculateTotalWeight() {
            return Object.values(this.weights).reduce((a, b) => parseFloat(a || 0) + parseFloat(b || 0), 0);
        },

        getNormalizedWeight(id) {
            const total = this.calculateTotalWeight();
            if (total <= 0) return 0;
            return (parseFloat(this.weights[id] || 0) / total);
        },

        onSliderChange() {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.fetchCalculation();
            }, 100);
        },

        async fetchCalculation() {
            this.loading = true;
            try {
                const response = await fetch('{{ route('api.simulator.calculate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ weights: this.weights })
                });

                if (response.ok) {
                    const data = await response.json();
                    this.rankings = data.hasil_akhir;
                    this.updateChart();
                }
            } catch (err) {
                console.error('Gagal mengambil data kalkulasi SMART:', err);
            } finally {
                this.loading = false;
            }
        },

        resetToDefault() {
            for (const [id, val] of Object.entries(this.defaultWeights)) {
                this.weights[id] = val;
            }
            this.fetchCalculation();
        },

        initChart() {
            const ctx = document.getElementById('rankingChart').getContext('2d');

            const labels = this.rankings.map(item => `${item.kode_alternatif} - ${item.nama_laptop}`);
            const dataScores = this.rankings.map(item => parseFloat(item.nilai_akhir_format));

            const backgroundColors = this.rankings.map(item => {
                if (item.peringkat === 1) return 'rgba(245, 158, 11, 0.85)'; // amber
                if (item.peringkat === 2) return 'rgba(99, 102, 241, 0.85)'; // indigo
                if (item.peringkat === 3) return 'rgba(6, 182, 212, 0.85)';  // cyan
                return 'rgba(100, 116, 139, 0.7)'; // slate
            });

            this.chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Skor Utilitas SMART (V)',
                        data: dataScores,
                        backgroundColor: backgroundColors,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 500,
                        easing: 'easeOutQuart'
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            max: 1.0,
                            grid: {
                                color: 'rgba(51, 65, 85, 0.3)',
                            },
                            ticks: {
                                color: '#94a3b8',
                                font: {
                                    family: "'JetBrains Mono', monospace",
                                    size: 11
                                }
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#e2e8f0',
                                font: {
                                    family: "'Plus Jakarta Sans', sans-serif",
                                    size: 12,
                                    weight: '600'
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            borderColor: '#334155',
                            borderWidth: 1,
                            titleColor: '#ffffff',
                            bodyColor: '#38bdf8',
                            callbacks: {
                                label: function(context) {
                                    return ` Nilai V: ${context.parsed.x.toFixed(4)} (${(context.parsed.x * 100).toFixed(2)}%)`;
                                }
                            }
                        }
                    }
                }
            });
        },

        updateChart() {
            if (!this.chart) return;

            this.chart.data.labels = this.rankings.map(item => `${item.kode_alternatif} - ${item.nama_laptop}`);
            this.chart.data.datasets[0].data = this.rankings.map(item => parseFloat(item.nilai_akhir_format));
            this.chart.data.datasets[0].backgroundColor = this.rankings.map(item => {
                if (item.peringkat === 1) return 'rgba(245, 158, 11, 0.85)';
                if (item.peringkat === 2) return 'rgba(99, 102, 241, 0.85)';
                if (item.peringkat === 3) return 'rgba(6, 182, 212, 0.85)';
                return 'rgba(100, 116, 139, 0.7)';
            });

            this.chart.update();
        }
    }
}
</script>
@endpush
@endsection
