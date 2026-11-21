@extends('layouts.app', ['title' => 'Data Alternatif Laptop', 'header' => 'Manajemen Data Alternatif Laptop'])

@section('content')
<div x-data="{
    createModal: false,
    editModal: false,
    deleteModal: false,
    editItem: { id: '', kode_alternatif: '', nama_laptop: '', merek: '', spesifikasi_ringkas: '' },
    deleteItem: { id: '', kode_alternatif: '', nama_laptop: '' },
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
            <div class="w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-white">Daftar Alternatif Laptop</h3>
                <p class="text-xs text-slate-400">Total Laptop: <strong class="text-white font-mono-code">{{ $alternatifs->count() }} unit</strong> terdaftar untuk pengujian ranking SMART.</p>
            </div>
        </div>

        <button @click="createModal = true" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm transition-all shadow-lg shadow-indigo-600/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Alternatif Baru</span>
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-950/70 border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-6">Kode</th>
                        <th class="py-4 px-6">Merek</th>
                        <th class="py-4 px-6">Nama Model Laptop</th>
                        <th class="py-4 px-6">Spesifikasi Ringkas</th>
                        <th class="py-4 px-6 text-center">Status Matriks</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-sm">
                    @forelse($alternatifs as $alternatif)
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="py-4 px-6 font-mono-code font-bold text-indigo-400">
                            {{ $alternatif->kode_alternatif }}
                        </td>
                        <td class="py-4 px-6 font-semibold text-white">
                            <span class="px-2.5 py-1 rounded-lg bg-slate-800 border border-slate-700/60 text-xs">{{ $alternatif->merek }}</span>
                        </td>
                        <td class="py-4 px-6 font-medium text-slate-200">
                            {{ $alternatif->nama_laptop }}
                        </td>
                        <td class="py-4 px-6 text-xs text-slate-400 max-w-xs">
                            {{ $alternatif->spesifikasi_ringkas ?? '-' }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                <span>{{ $alternatif->penilaians->count() }} Kriteria Terhubung</span>
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button @click="openEdit({{ json_encode($alternatif) }})" class="p-2 text-slate-400 hover:text-indigo-400 hover:bg-indigo-500/10 rounded-xl transition-colors" title="Edit Alternatif">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button @click="openDelete({{ json_encode($alternatif) }})" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-xl transition-colors" title="Hapus Alternatif">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400 text-sm">
                            Belum ada alternatif laptop. Silakan tambahkan laptop baru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Alternatif -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="createModal = false" class="bg-slate-900 border border-slate-800 rounded-3xl max-w-lg w-full p-6 shadow-2xl relative">
            <h3 class="text-lg font-bold text-white mb-1">Tambah Alternatif Laptop</h3>
            <p class="text-xs text-slate-400 mb-5">Laptop baru akan otomatis mendapatkan entri nilai default 0 di matriks penilaian.</p>

            <form action="{{ route('alternatif.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Kode Alternatif</label>
                        <input type="text" name="kode_alternatif" required placeholder="Misal: A5"
                            class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 font-mono-code">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Merek Laptop</label>
                        <input type="text" name="merek" required placeholder="ASUS, Acer, Lenovo, MSI, dll"
                            class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Model Laptop</label>
                    <input type="text" name="nama_laptop" required placeholder="Contoh: Dell XPS 16 / ROG Zephyrus G16"
                        class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Spesifikasi Ringkas (Opsional)</label>
                    <textarea name="spesifikasi_ringkas" rows="3" placeholder="Deskripsi ringkas kartu grafis, CPU, port, atau fitur unggulan"
                        class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500"></textarea>
                </div>

                <div class="mt-6 flex justify-end space-x-3 pt-3 border-t border-slate-800">
                    <button type="button" @click="createModal = false" class="px-4 py-2 text-xs font-semibold text-slate-400 hover:text-white rounded-xl">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-lg shadow-indigo-600/30">Simpan Alternatif</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Alternatif -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="editModal = false" class="bg-slate-900 border border-slate-800 rounded-3xl max-w-lg w-full p-6 shadow-2xl relative">
            <h3 class="text-lg font-bold text-white mb-1">Edit Alternatif Laptop</h3>
            <p class="text-xs text-slate-400 mb-5">Perbarui informasi identitas model dan merek laptop.</p>

            <form :action="'{{ url('alternatif') }}/' + editItem.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Kode Alternatif</label>
                        <input type="text" name="kode_alternatif" x-model="editItem.kode_alternatif" required
                            class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-indigo-500 font-mono-code">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Merek Laptop</label>
                        <input type="text" name="merek" x-model="editItem.merek" required
                            class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Model Laptop</label>
                    <input type="text" name="nama_laptop" x-model="editItem.nama_laptop" required
                        class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Spesifikasi Ringkas (Opsional)</label>
                    <textarea name="spesifikasi_ringkas" x-model="editItem.spesifikasi_ringkas" rows="3"
                        class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-indigo-500"></textarea>
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
            <h3 class="text-base font-bold text-white mb-1">Hapus Alternatif?</h3>
            <p class="text-xs text-slate-400 mb-5">
                Apakah Anda yakin ingin menghapus laptop <strong class="text-rose-400" x-text="deleteItem.kode_alternatif + ' - ' + deleteItem.nama_laptop"></strong>? Seluruh data matriks terkait akan terhapus.
            </p>

            <form :action="'{{ url('alternatif') }}/' + deleteItem.id" method="POST" class="flex justify-center space-x-3">
                @csrf
                @method('DELETE')
                <button type="button" @click="deleteModal = false" class="px-4 py-2 text-xs font-semibold text-slate-400 hover:text-white rounded-xl">Batal</button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold bg-rose-600 hover:bg-rose-500 text-white rounded-xl shadow-lg shadow-rose-600/30">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection
