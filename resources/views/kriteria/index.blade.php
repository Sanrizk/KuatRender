@extends('layouts.app', ['title' => 'Data Kriteria & Bobot', 'header' => 'Manajemen Data Kriteria & Bobot SMART'])

@section('content')
<div x-data="{
    createModal: false,
    editModal: false,
    deleteModal: false,
    editItem: { id: '', kode_kriteria: '', nama_kriteria: '', satuan: '', sifat: 'benefit', bobot_awal: '' },
    deleteItem: { id: '', kode_kriteria: '', nama_kriteria: '' },
    openEdit(item) {
        this.editItem = { ...item };
        this.editModal = true;
    },
    openDelete(item) {
        this.deleteItem = { ...item };
        this.deleteModal = true;
    }
}" class="space-y-6">

    <!-- Top Action & Indicator Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-900/80 border border-slate-800 p-5 rounded-2xl shadow-xl">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-white">Daftar Kriteria & Bobot Awal</h3>
                <p class="text-xs text-slate-400">Total Bobot Awal Saat Ini: <strong class="text-white font-mono-code">{{ number_format($totalBobotAwal, 2) }}</strong> (Ternormalisasi $w_j$ otomatis)</p>
            </div>
        </div>

        <button @click="createModal = true" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm transition-all shadow-lg shadow-indigo-600/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Kriteria Baru</span>
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-950/70 border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-6">Kode</th>
                        <th class="py-4 px-6">Nama Kriteria</th>
                        <th class="py-4 px-6">Satuan</th>
                        <th class="py-4 px-6">Sifat Kriteria</th>
                        <th class="py-4 px-6 text-right">Bobot Awal</th>
                        <th class="py-4 px-6 text-right">Bobot Normalisasi ($w_j$)</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-sm">
                    @forelse($kriterias as $kriteria)
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="py-4 px-6 font-mono-code font-bold text-indigo-400">
                            {{ $kriteria->kode_kriteria }}
                        </td>
                        <td class="py-4 px-6 font-medium text-white">
                            {{ $kriteria->nama_kriteria }}
                        </td>
                        <td class="py-4 px-6 text-slate-300 font-mono-code text-xs">
                            <span class="bg-slate-800 px-2.5 py-1 rounded-lg border border-slate-700/60">{{ $kriteria->satuan }}</span>
                        </td>
                        <td class="py-4 px-6">
                            @if($kriteria->sifat === 'benefit')
                            <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                <span>Benefit</span>
                            </span>
                            @else
                            <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/15 text-amber-400 border border-amber-500/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                <span>Cost</span>
                            </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right font-mono-code text-white font-medium">
                            {{ number_format($kriteria->bobot_awal, 2) }}
                        </td>
                        <td class="py-4 px-6 text-right font-mono-code">
                            <span class="text-cyan-400 font-bold">{{ $kriteria->bobot_normalisasi_format }}</span>
                            <span class="text-slate-500 text-xs ml-1">({{ $kriteria->persentase_bobot }})</span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button @click="openEdit({{ json_encode($kriteria) }})" class="p-2 text-slate-400 hover:text-indigo-400 hover:bg-indigo-500/10 rounded-xl transition-colors" title="Edit Kriteria">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button @click="openDelete({{ json_encode($kriteria) }})" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-xl transition-colors" title="Hapus Kriteria">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400 text-sm">
                            Belum ada kriteria. Silakan tambahkan kriteria baru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($kriterias->isNotEmpty())
                <tfoot>
                    <tr class="bg-slate-950/80 border-t border-slate-800 text-xs font-bold text-slate-300">
                        <td colspan="4" class="py-3 px-6 text-right uppercase tracking-wider text-slate-400">Total Keseluruhan:</td>
                        <td class="py-3 px-6 text-right font-mono-code text-white">{{ number_format($totalBobotAwal, 2) }}</td>
                        <td class="py-3 px-6 text-right font-mono-code text-cyan-400">1.0000 (100%)</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Modal Tambah Kriteria -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="createModal = false" class="bg-slate-900 border border-slate-800 rounded-3xl max-w-lg w-full p-6 shadow-2xl relative">
            <h3 class="text-lg font-bold text-white mb-1">Tambah Kriteria Baru</h3>
            <p class="text-xs text-slate-400 mb-5">Atribut penilaian baru akan otomatis diintegrasikan ke dalam matriks.</p>

            <form action="{{ route('kriteria.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Kode Kriteria</label>
                        <input type="text" name="kode_kriteria" required placeholder="Misal: C7"
                            class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 font-mono-code">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Satuan</label>
                        <input type="text" name="satuan" required placeholder="GB, GHz, Watt, dll"
                            class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 font-mono-code">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Kriteria</label>
                    <input type="text" name="nama_kriteria" required placeholder="Contoh: Layar (Color Accuracy 100% DCI-P3)"
                        class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Sifat Kriteria</label>
                        <select name="sifat" required
                            class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                            <option value="benefit">Benefit (Makin besar makin baik)</option>
                            <option value="cost">Cost (Makin kecil makin baik)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Bobot Awal</label>
                        <input type="number" step="0.01" min="0" name="bobot_awal" required placeholder="Misal: 15"
                            class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 font-mono-code">
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3 pt-3 border-t border-slate-800">
                    <button type="button" @click="createModal = false" class="px-4 py-2 text-xs font-semibold text-slate-400 hover:text-white rounded-xl">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-lg shadow-indigo-600/30">Simpan Kriteria</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Kriteria -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="editModal = false" class="bg-slate-900 border border-slate-800 rounded-3xl max-w-lg w-full p-6 shadow-2xl relative">
            <h3 class="text-lg font-bold text-white mb-1">Edit Kriteria</h3>
            <p class="text-xs text-slate-400 mb-5">Perubahan bobot akan otomatis memicu kalkulasi ulang normalisasi bobot SMART.</p>

            <form :action="'{{ url('kriteria') }}/' + editItem.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Kode Kriteria</label>
                        <input type="text" name="kode_kriteria" x-model="editItem.kode_kriteria" required
                            class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-indigo-500 font-mono-code">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Satuan</label>
                        <input type="text" name="satuan" x-model="editItem.satuan" required
                            class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-indigo-500 font-mono-code">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Kriteria</label>
                    <input type="text" name="nama_kriteria" x-model="editItem.nama_kriteria" required
                        class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Sifat Kriteria</label>
                        <select name="sifat" x-model="editItem.sifat" required
                            class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                            <option value="benefit">Benefit</option>
                            <option value="cost">Cost</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Bobot Awal</label>
                        <input type="number" step="0.01" min="0" name="bobot_awal" x-model="editItem.bobot_awal" required
                            class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-indigo-500 font-mono-code">
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3 pt-3 border-t border-slate-800">
                    <button type="button" @click="editModal = false" class="px-4 py-2 text-xs font-semibold text-slate-400 hover:text-white rounded-xl">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-lg shadow-indigo-600/30">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="deleteModal = false" class="bg-slate-900 border border-slate-800 rounded-3xl max-w-sm w-full p-6 shadow-2xl relative text-center">
            <div class="w-12 h-12 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            <h3 class="text-base font-bold text-white mb-1">Hapus Kriteria?</h3>
            <p class="text-xs text-slate-400 mb-5">
                Apakah Anda yakin ingin menghapus kriteria <strong class="text-rose-400" x-text="deleteItem.kode_kriteria + ' - ' + deleteItem.nama_kriteria"></strong>? Seluruh data penilaian terkait akan terhapus.
            </p>

            <form :action="'{{ url('kriteria') }}/' + deleteItem.id" method="POST" class="flex justify-center space-x-3">
                @csrf
                @method('DELETE')
                <button type="button" @click="deleteModal = false" class="px-4 py-2 text-xs font-semibold text-slate-400 hover:text-white rounded-xl">Batal</button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold bg-rose-600 hover:bg-rose-500 text-white rounded-xl shadow-lg shadow-rose-600/30">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection
