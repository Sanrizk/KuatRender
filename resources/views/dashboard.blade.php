@extends('layouts.app', ['title' => 'Dashboard SPK', 'header' => 'Ringkasan Sistem & Perankingan SMART'])

@section('content')
<div class="space-y-6">
    <!-- Hero / Welcome Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-900/60 via-slate-900 to-slate-900 border border-indigo-500/20 p-8 shadow-2xl">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 mb-3">
                Sistem Pendukung Keputusan Multi-Atribut
            </span>
            <h2 class="text-3xl font-extrabold text-white tracking-tight sm:text-4xl">
                Kuat<span class="text-indigo-400">Render</span>
            </h2>
            <p class="mt-2 text-sm text-slate-300 leading-relaxed">
                Pemilihan laptop bertenaga tinggi untuk kebutuhan 3D Rendering (Blender, Cinema 4D, Maya) & Video Editing 4K/8K (Premiere Pro, DaVinci Resolve) menggunakan algoritma <strong class="text-white font-semibold">SMART</strong> (Simple Multi-Attribute Rating Technique).
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('perhitungan.hasil') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm transition-all shadow-lg shadow-indigo-600/30 flex items-center space-x-2">
                    <span>Lihat Hasil Lengkap</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                <a href="{{ route('perhitungan.simulator') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-medium text-sm transition-all flex items-center space-x-2">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    <span>Uji Simulator Bobot</span>
                </a>
            </div>
        </div>
        <!-- Decorative subtle background glow -->
        <div class="absolute -right-10 -bottom-10 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 hover:border-slate-700 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-400">Total Kriteria</p>
                    <h3 class="text-2xl font-bold text-white mt-1">{{ $kriteriasCount }}</h3>
                    <p class="text-[11px] text-indigo-400 mt-1">Bobot Awal: {{ number_format($calculation['ringkasan']['total_bobot_awal'], 0) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 hover:border-slate-700 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-400">Alternatif Laptop</p>
                    <h3 class="text-2xl font-bold text-white mt-1">{{ $alternatifsCount }}</h3>
                    <p class="text-[11px] text-cyan-400 mt-1">Data Laptop Terdaftar</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 hover:border-slate-700 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-400">Titik Penilaian Matriks</p>
                    <h3 class="text-2xl font-bold text-white mt-1">{{ $penilaiansCount }}</h3>
                    <p class="text-[11px] text-emerald-400 mt-1">Spesifikasi Terisi</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 hover:border-slate-700 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-400">Rekomendasi Utama</p>
                    <h3 class="text-lg font-bold text-amber-400 mt-1 truncate">
                        {{ $topRanking[0]['nama_laptop'] ?? 'Belum ada' }}
                    </h3>
                    <p class="text-[11px] text-slate-400 mt-1">
                        Skor: {{ $topRanking[0]['nilai_akhir_format'] ?? '0' }} ({{ $topRanking[0]['persentase_skor_format'] ?? '0%' }})
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Ranking Podiums -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 shadow-xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-white">Top Rekomendasi Laptop Terbaik</h3>
                <p class="text-xs text-slate-400">Hasil perhitungan otomatis menggunakan metode SMART berdasarkan spesifikasi teknis dan bobot kriteria.</p>
            </div>
            <a href="{{ route('perhitungan.hasil') }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold flex items-center space-x-1">
                <span>Detail Perhitungan &rarr;</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($topRanking as $item)
            <div class="relative bg-slate-950/60 border {{ $item['peringkat'] === 1 ? 'border-amber-500/40 ring-1 ring-amber-500/30' : 'border-slate-800' }} rounded-2xl p-5 overflow-hidden flex flex-col justify-between">
                @if($item['peringkat'] === 1)
                <div class="absolute top-0 right-0 bg-gradient-to-l from-amber-500 to-amber-600 text-slate-950 font-extrabold text-[10px] tracking-wider uppercase px-3 py-1 rounded-bl-xl shadow-lg">
                    ★ JUARA 1 REKOMENDASI
                </div>
                @endif

                <div>
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm
                            {{ $item['peringkat'] === 1 ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40' : ($item['peringkat'] === 2 ? 'bg-slate-700/50 text-slate-200 border border-slate-600' : 'bg-orange-500/20 text-orange-300 border border-orange-500/40') }}">
                            #{{ $item['peringkat'] }}
                        </div>
                        <div>
                            <span class="text-xs font-mono-code text-indigo-400 font-bold">{{ $item['kode_alternatif'] }}</span>
                            <span class="text-xs text-slate-400 ml-1">({{ $item['merek'] }})</span>
                            <h4 class="text-base font-bold text-white">{{ $item['nama_laptop'] }}</h4>
                        </div>
                    </div>

                    <p class="mt-3 text-xs text-slate-400 line-clamp-2">
                        {{ $item['spesifikasi_ringkas'] ?? 'Spesifikasi optimal untuk rendering dan editing.' }}
                    </p>
                </div>

                <div class="mt-5 pt-4 border-t border-slate-800/80">
                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="text-slate-400">Skor Utilitas SMART (V):</span>
                        <span class="font-mono-code font-bold text-white">{{ $item['nilai_akhir_format'] }}</span>
                    </div>
                    <div class="w-full bg-slate-800 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-gradient-to-r {{ $item['peringkat'] === 1 ? 'from-amber-500 to-amber-400' : 'from-indigo-600 to-cyan-400' }} h-2.5 rounded-full transition-all duration-500" style="width: {{ $item['persentase_skor'] }}%"></div>
                    </div>
                    <div class="flex justify-between items-center mt-2 text-[11px] text-slate-400">
                        <span>Tingkat Kelayakan</span>
                        <span class="font-semibold text-slate-200">{{ $item['persentase_skor_format'] }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
