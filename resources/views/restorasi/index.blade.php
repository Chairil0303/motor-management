@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Data Restorasi</h1>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-3">{{ session('success') }}</div>
        @endif

        <table class="w-full border border-gray-300 text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">Merek</th>
                    <th class="p-2 border">Tipe</th>
                    <th class="p-2 border">Plat Nomor</th>
                    <th class="p-2 border">Total Restorasi</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($motor as $m)
                    @php
                        $totalRestorasi = $m->restorasis->sum('biaya_restorasi');
                    @endphp
                    <tr>
                        <td class="p-2 border">{{ $loop->iteration }}</td>
                        <td class="p-2 border">{{ $m->merek }}</td>
                        <td class="p-2 border">{{ $m->tipe_model }}</td>
                        <td class="p-2 border">{{ $m->plat_nomor ?? '-' }}</td>
                        <td class="p-2 border">Rp {{ number_format($totalRestorasi, 0, ',', '.') }}</td>
                        <td class="p-2 border flex gap-2">
                            <button class="text-blue-600 hover:underline" onclick="openDetailModal({{ $m->id }})">
                                Detail
                            </button>
                            <button class="text-green-600 hover:underline" onclick="openCreateModal({{ $m->id }})">
                                + Restorasi
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Detail -->
    <div id="detailModal"
        class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-40 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-lg p-6 w-full max-w-2xl relative transform scale-95 transition-all duration-300">
            <h2 class="text-xl font-semibold mb-4">Detail Restorasi</h2>
            <div id="detailContent"></div>
            <button onclick="closeDetailModal()" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded">Kembali</button>
        </div>
    </div>

    <!-- Modal Create -->
    <div id="createModal"
        class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-lg p-6 w-full max-w-md relative transform scale-95 transition-all duration-300">
            <h2 class="text-xl font-semibold mb-4">Tambah Restorasi</h2>
            <form id="restorasiForm" method="POST" action="{{ route('restorasi.store') }}">
                @csrf
                <input type="hidden" name="motor_id" id="motor_id">
                <div class="mb-3">
                    <label class="block mb-1">Deskripsi</label>
                    <textarea name="deskripsi" class="w-full border rounded p-2"></textarea>
                </div>
                <div class="mb-3">
                    <label class="block mb-1">Tanggal Restorasi</label>
                    <input type="date" name="tanggal_restorasi" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label class="block font-semibold mb-1">Biaya Restorasi</label>
                    <input type="text" id="biaya_restorasi" name="biaya_restorasi" class="border rounded p-2 w-full"
                        placeholder="Contoh: 1.000.000" oninput="formatRupiah(this)">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeCreateModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="editModal"
        class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-lg p-6 w-full max-w-md relative transform scale-95 transition-all duration-300">
            <h2 class="text-xl font-semibold mb-4">Edit Restorasi</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block mb-1">Deskripsi</label>
                    <textarea name="deskripsi" id="edit_deskripsi" class="w-full border rounded p-2"></textarea>
                </div>
                <div class="mb-3">
                    <label class="block mb-1">Tanggal Restorasi</label>
                    <input type="date" name="tanggal_restorasi" id="edit_tanggal" class="w-full border rounded p-2"
                        required>
                </div>
                <div class="mb-3">
                    <label class="block mb-1">Biaya Restorasi</label>
                    <input type="text" name="biaya_restorasi" id="edit_biaya" class="w-full border rounded p-2"
                        oninput="formatRupiah(this)">
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="closeEditModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let currentMotorId = null;

        function formatRupiah(input) {
            let value = input.value.replace(/\D/g, '');
            value = new Intl.NumberFormat('id-ID').format(value);
            input.value = value;
        }

        // DETAIL MODAL
        function openDetailModal(motorId) {
            currentMotorId = motorId;
            const modal = document.getElementById('detailModal');
            const content = document.getElementById('detailContent');
            content.innerHTML = '<div class="text-center p-4">Loading...</div>';
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0', 'scale-95');
                modal.classList.add('opacity-100');
            }, 10);

            fetch("{{ url('restorasi/detail') }}/" + motorId)
                .then(res => res.text())
                .then(html => content.innerHTML = html)
                .catch(err => content.innerHTML = '<div class="text-center p-4 text-red-600">Gagal memuat data</div>');
        }

        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            modal.classList.add('opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        // CREATE MODAL
        function openCreateModal(motorId) {
            document.getElementById('motor_id').value = motorId;
            const modal = document.getElementById('createModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
            }, 10);
        }
        function closeCreateModal() {
            const modal = document.getElementById('createModal');
            modal.classList.add('opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        // EDIT MODAL
        const editModal = document.getElementById('editModal');
        const editModalContent = editModal.querySelector('div');

        function openEditModal(id, deskripsi, tanggal, biaya) {
            const form = document.getElementById('editForm');
            form.action = `{{ url('restorasi/update') }}/${id}`;
            document.getElementById('edit_deskripsi').value = deskripsi;
            document.getElementById('edit_tanggal').value = tanggal;
            document.getElementById('edit_biaya').value = new Intl.NumberFormat('id-ID').format(biaya);

            editModal.classList.remove('hidden');
            setTimeout(() => {
                editModal.classList.remove('opacity-0');
                editModal.classList.add('opacity-100');
                editModalContent.classList.remove('scale-95');
                editModalContent.classList.add('scale-100');
            }, 10);

            // handle submit edit form
            form.onsubmit = function (e) {
                e.preventDefault();
                const formData = new FormData(form);
                fetch(form.action, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                }).then(res => {
                    if (res.ok) {
                        closeEditModal();
                        openDetailModal(currentMotorId); // reload detail
                    }
                });
            };
        }

        function closeEditModal() {
            editModal.classList.add('opacity-0');
            editModalContent.classList.add('scale-95');
            setTimeout(() => editModal.classList.add('hidden'), 300);
        }

        // DELETE
        function confirmDelete(id) {
            Swal.fire({
                title: 'Anda yakin?',
                text: "Data restorasi akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('restorasi/delete') }}/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    }).then(res => {
                        if (res.ok) {
                            Swal.fire('Terhapus!', 'Data restorasi telah dihapus.', 'success').then(() => {
                                openDetailModal(currentMotorId); // reload modal detail, bukan reload page
                            });
                        } else {
                            Swal.fire('Gagal', 'Tidak dapat menghapus data.', 'error');
                        }
                    });
                }
            })
        }
    </script>
@endsection