{{-- SWEET ALERT & SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search-barang');
        const resultsBox = document.getElementById('search-results');
        const barangInfo = document.getElementById('barang-info');
        const clearBtn = document.getElementById('clear-btn');
        const form = document.getElementById('formBelanja');
        let timeout = null;

        // 🔍 Search Barang
        searchInput.addEventListener('keyup', function () {
            clearTimeout(timeout);
            const query = this.value.trim();
            if (query.length < 2) {
                resultsBox.classList.add('hidden');
                return;
            }

            timeout = setTimeout(() => {
                fetch(`{{ route('bengkel.belanja.search-barang') }}?q=${query}`)
                    .then(res => res.json())
                    .then(data => {
                        resultsBox.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(item => {
                                const li = document.createElement('li');
                                li.textContent = `${item.nama_barang}`;
                                li.classList.add('px-3', 'py-2', 'hover:bg-blue-100', 'cursor-pointer');
                                li.addEventListener('click', () => selectBarang(item));
                                resultsBox.appendChild(li);
                            });
                        } else {
                            resultsBox.innerHTML = `
                                                                <li class="px-3 py-2 text-gray-500">Barang tidak ditemukan</li>
                                                                <li id="add-new-item" class="px-3 py-2 text-blue-600 hover:bg-blue-100 cursor-pointer font-semibold">
                                                                    + Tambah Barang Baru
                                                                </li>`;
                            resultsBox.classList.remove('hidden');

                            // Klik tambah barang baru
                            document.getElementById('add-new-item').addEventListener('click', () => {
                                document.getElementById('modal-tambah-barang').classList.remove('hidden');
                                resultsBox.classList.add('hidden');
                            });
                        }
                        resultsBox.classList.remove('hidden');
                    })
                    .catch(() => {
                        resultsBox.innerHTML = '<li class="px-3 py-2 text-red-500">Terjadi error koneksi</li>';
                        resultsBox.classList.remove('hidden');
                    });
            }, 300);
        });

        // 🧹 Clear barang terpilih
        clearBtn.addEventListener('click', function () {
            searchInput.value = '';
            document.getElementById('barang_id').value = '';
            document.getElementById('stok_saat_ini').value = '';
            document.getElementById('harga_beli').value = '';
            document.getElementById('harga_jual').value = '';
            barangInfo.classList.add('hidden');
            this.classList.add('hidden');
        });

        // ✅ Select Barang
        function selectBarang(item) {
            document.getElementById('barang_id').value = item.id;
            document.getElementById('stok_saat_ini').value = item.stok;
            document.getElementById('harga_beli').value = formatRupiah(item.harga_beli.toString());
            document.getElementById('harga_jual').value = formatRupiah(item.harga_jual.toString());
            barangInfo.classList.remove('hidden');
            resultsBox.classList.add('hidden');
            searchInput.value = item.nama_barang;
            clearBtn.classList.remove('hidden');
        }

        // 💰 Format angka jadi rupiah-like (1.000.000)
        document.querySelectorAll('.harga-input').forEach(input => {
            input.addEventListener('input', function (e) {
                let value = this.value.replace(/\D/g, '');
                this.value = formatRupiah(value);
            });
        });

        function formatRupiah(angka) {
            if (!angka) return '';
            return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // 🚀 SweetAlert on Submit Success
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Simpan Belanja?',
                text: "Pastikan data sudah benar!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Modal Tambah Barang Baru
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('modal-tambah-barang');
        const closeModal = document.getElementById('close-modal');
        const clearModal = document.getElementById('clear-modal');
        const saveModal = document.getElementById('btn-save-modal');
        const formModal = document.getElementById('form-tambah-barang');

        // 💰 Format angka di modal
        document.querySelectorAll('.harga-modal').forEach(input => {
            input.addEventListener('input', function (e) {
                let value = this.value.replace(/\D/g, '');
                this.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            });
        });

        // Tutup modal
        closeModal.addEventListener('click', () => modal.classList.add('hidden'));

        // Clear isi form
        clearModal.addEventListener('click', () => {
            formModal.reset();
        });

        // Simpan barang baru (AJAX)
        // Simpan barang baru (AJAX)
        saveModal.addEventListener('click', () => {
            const formData = new FormData(formModal);

            fetch(`{{ route('bengkel.barang.storeAjax') }}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Barang & Riwayat Berhasil Ditambahkan!',
                            text: data.barang.nama_barang,
                            showConfirmButton: false,
                            timer: 1500
                        });

                        // ✅ Redirect otomatis ke halaman Riwayat Belanja
                        setTimeout(() => {
                            window.location.href = "{{ route('bengkel.belanja.index') }}";
                        }, 1500);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menambah Barang!',
                            text: data.message || 'Terjadi kesalahan!'
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Tidak dapat menyimpan barang baru.'
                    });
                });
        });


    });
</script>