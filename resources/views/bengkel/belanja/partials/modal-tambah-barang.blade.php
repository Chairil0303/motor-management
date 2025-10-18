<!-- resources/views/bengkel/belanja/partials/modal-tambah-barang.blade.php -->
<div id="modal-tambah-barang" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white w-full max-w-lg p-6 rounded-lg shadow-lg relative">
        <button id="close-modal"
            class="absolute top-2 right-3 text-gray-600 hover:text-black text-xl font-bold">×</button>

        <h2 class="text-lg font-semibold mb-4">Tambah Barang Baru</h2>

        <form id="form-tambah-barang" onsubmit="return false;">
            <div class="mb-3">
                <label class="block font-semibold mb-1">Nama Barang</label>
                <input type="text" name="nama_barang" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-3">
                <label class="block font-semibold mb-1">Kategori</label>
                <select name="kategori_id" class="w-full border p-2 rounded">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($kategori as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="block font-semibold mb-1">Stok</label>
                <input type="number" name="stok" class="w-full border p-2 rounded" value="1" min="0" required>
            </div>

            <div class="mb-3">
                <label class="block font-semibold mb-1">Harga Beli (Rp)</label>
                <input type="text" name="harga_beli" class="w-full border p-2 rounded harga-modal" required>
            </div>

            <div class="mb-3">
                <label class="block font-semibold mb-1">Harga Jual (Rp)</label>
                <input type="text" name="harga_jual" class="w-full border p-2 rounded harga-modal">
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" id="clear-modal"
                    class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">Clear</button>
                <button type="button" id="btn-save-modal"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>