@extends('layouts.app', ['title' => 'Matriks Penilaian', 'header' => 'Input & Pembaharuan Matriks Penilaian 2D'])

@section('content')
<div class="space-y-6">
    <!-- Top Info Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-900/80 border border-slate-800 p-5 rounded-2xl shadow-xl">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-white">Matriks Penilaian Alternatif vs Kriteria</h3>
                <p class="text-xs text-slate-400">Masukkan nilai spesifikasi teknis untuk setiap laptop. Nilai ini menjadi basis perhitungan utilitas SMART ($X$).</p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('perhitungan.hasil') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-medium text-xs transition-all flex items-center space-x-2">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span>Lihat Hasil SMART</span>
            </a>
        </div>
    </div>

    <!-- Batch Form 2D Matrix -->
    <form action="{{ route('penilaian.batch') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-950/70 border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-4 px-6 min-w-[200px] sticky left-0 bg-slate-950/95 z-10 border-r border-slate-800">
                                Alternatif Laptop
                            </th>
                            @foreach($kriterias as $kriteria)
                            <th class="py-4 px-4 text-center min-w-[150px]">
                                <div class="flex flex-col items-center">
                                    <div class="flex items-center space-x-1">
                                        <span class="font-mono-code text-indigo-400">{{ $kriteria->kode_kriteria }}</span>
                                        <span class="text-[10px] font-semibold px-1.5 py-0.2 rounded-full {{ $kriteria->sifat === 'benefit' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300' }}">
                                            {{ ucfirst($kriteria->sifat) }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-white normal-case font-medium mt-1 truncate max-w-[130px]" title="{{ $kriteria->nama_kriteria }}">
                                        {{ $kriteria->nama_kriteria }}
                                    </span>
                                    <span class="text-[11px] font-mono-code text-slate-400 mt-0.5">({{ $kriteria->satuan }})</span>
                                </div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80 text-sm">
                        @forelse($alternatifs as $alternatif)
                        @php
                            $penilaiansByKrit = $alternatif->penilaians->keyBy('kriteria_id');
                        @endphp
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-4 px-6 sticky left-0 bg-slate-900/95 z-10 border-r border-slate-800">
                                <div class="flex items-center space-x-2.5">
                                    <span class="font-mono-code font-bold text-indigo-400 text-xs px-2 py-1 rounded bg-slate-800 border border-slate-700">
                                        {{ $alternatif->kode_alternatif }}
                                    </span>
                                    <div>
                                        <div class="font-semibold text-white text-sm">{{ $alternatif->nama_laptop }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $alternatif->merek }}</div>
                                    </div>
                                </div>
                            </td>

                            @foreach($kriterias as $kriteria)
                            @php
                                $currentVal = optional($penilaiansByKrit->get($kriteria->id))->nilai ?? 0;
                            @endphp
                            <td class="py-3 px-3 text-center">
                                <div class="relative rounded-xl shadow-sm">
                                    <input type="number" step="0.0001" min="0" required
                                        name="matrix[{{ $alternatif->id }}][{{ $kriteria->id }}]"
                                        value="{{ old("matrix.{$alternatif->id}.{$kriteria->id}", (float)$currentVal) }}"
                                        class="w-full bg-slate-950 border border-slate-700/80 rounded-xl px-3 py-2 text-center text-sm font-mono-code text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all hover:border-slate-600">
                                </div>
                            </td>
                            @endforeach
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $kriterias->count() + 1 }}" class="py-8 text-center text-slate-400 text-sm">
                                Belum ada alternatif terdaftar. Silakan tambahkan alternatif terlebih dahulu.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($alternatifs->isNotEmpty() && $kriterias->isNotEmpty())
            <div class="p-5 bg-slate-950/80 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-slate-400">
                    <span class="text-amber-400 font-semibold">Tips:</span> Nilai desimal dapat dimasukkan hingga 4 angka di belakang koma (misal: boost clock 5.60 GHz).
                </div>
                <div class="flex items-center space-x-3">
                    <button type="reset" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs transition-all">
                        Reset Nilai
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs transition-all shadow-lg shadow-indigo-600/30 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Simpan Seluruh Matriks</span>
                    </button>
                </div>
            </div>
            @endif
        </div>
    </form>
</div>
@endsection
