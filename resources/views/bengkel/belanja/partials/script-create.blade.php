<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-barang');
    const resultsBox = document.getElementById('search-results');
    const barangInfo = document.getElementById('barang-info');
    const clearBtn = document.getElementById('clear-btn');
    const form = document.getElementById('formBelanja');
    let timeout = null;

    // -----------------------
    // Utility: format angka buat tampilan (1.000.000)
    // - membersihkan semua non-digit
    // - hanya format bagian integer (buang decimal .00 kalau ada)
    function formatRupiahForDisplay(raw) {
        if (raw === null || raw === undefined) return '';
        let s = raw.toString();

        // jika ada bagian desimal seperti "200000.00", ambil sebelum titik
        if (s.indexOf('.') !== -1) {
            s = s.split('.')[0];
        }

        // hapus semua non-digit (aman jika ada koma/rp/spasi)
        s = s.replace(/\D/g, '');

        if (s === '') return '';

        // grouping ribuan pake titik
        return s.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Utility: bersihin string harga sebelum dikirim ke server
    // (menghasilkan string angka tanpa pemisah ribuan)
    function sanitizeNumericString(raw) {
        if (raw === null || raw === undefined) return '';
        return raw.toString().replace(/\D/g, '');
    }

    // -----------------------
    // Search barang
    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            clearTimeout(timeout);
            const query = this.value.trim();
            if (query.length < 2) {
                resultsBox.classList.add('hidden');
                return;
            }

            timeout = setTimeout(() => {
                fetch(`{{ route('bengkel.belanja.search-barang') }}?q=${encodeURIComponent(query)}`)
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
    }

    // Clear barang terpilih
    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            searchInput.value = '';
            document.getElementById('barang_id').value = '';
            document.getElementById('stok_saat_ini').value = '';
            document.getElementById('harga_beli').value = '';
            document.getElementById('harga_jual').value = '';
            barangInfo.classList.add('hidden');
            this.classList.add('hidden');
        });
    }

    // Select Barang
    function selectBarang(item) {
        document.getElementById('barang_id').value = item.id;
        document.getElementById('stok_saat_ini').value = item.stok;

        // <-- gunakan fungsi format yang aman
        document.getElementById('harga_beli').value = formatRupiahForDisplay(item.harga_beli);
        document.getElementById('harga_jual').value = formatRupiahForDisplay(item.harga_jual);

        barangInfo.classList.remove('hidden');
        resultsBox.classList.add('hidden');
        searchInput.value = item.nama_barang;
        clearBtn.classList.remove('hidden');
    }

    // Attach input formatter ke semua input harga yang ada di page
    document.querySelectorAll('.harga-input').forEach(input => {
        input.addEventListener('input', function (e) {
            // ambil digit saja
            let onlyDigits = this.value.replace(/\D/g, '');
            this.value = onlyDigits === '' ? '' : onlyDigits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        });
    });

    // -----------------------
    // Form submit (belanja)
    if (form) {
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
                    // sebelum submit, ubah field harga_beli/harga_jual ke bentuk numeric tanpa titik
                    const hb = document.getElementById('harga_beli');
                    const hj = document.getElementById('harga_jual');
                    if (hb) hb.value = sanitizeNumericString(hb.value);
                    if (hj) hj.value = sanitizeNumericString(hj.value);

                    form.submit();
                }
            });
        });
    }

    // -----------------------
    // Modal tambahkan barang (script lama tetap tapi dengan sanitize dan redirect)
    const modal = document.getElementById('modal-tambah-barang');
    const closeModal = document.getElementById('close-modal');
    const clearModal = document.getElementById('clear-modal');
    const saveModal = document.getElementById('btn-save-modal');
    const formModal = document.getElementById('form-tambah-barang');

    // Format angka di modal
    document.querySelectorAll('.harga-modal').forEach(input => {
        input.addEventListener('input', function (e) {
            let onlyDigits = this.value.replace(/\D/g, '');
            this.value = onlyDigits === '' ? '' : onlyDigits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        });
    });

    if (closeModal) closeModal.addEventListener('click', () => modal.classList.add('hidden'));
    if (clearModal) clearModal.addEventListener('click', () => formModal.reset());

    // Simpan barang baru (AJAX)
    if (saveModal) {
        saveModal.addEventListener('click', () => {
            // sebelum dikirim, pastikan field harga bersih dari formatting (kirim hanya angka)
            // kita bikin salinan FormData tapi override harga fields
            const fd = new FormData(formModal);
            if (fd.has('harga_beli')) {
                fd.set('harga_beli', sanitizeNumericString(fd.get('harga_beli')));
            }
            if (fd.has('harga_jual')) {
                fd.set('harga_jual', sanitizeNumericString(fd.get('harga_jual') || 0));
            }

            fetch(`{{ route('bengkel.barang.storeAjax') }}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: fd
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Barang & Riwayat Berhasil Ditambahkan!',
                        text: data.barang.nama_barang,
                        showConfirmButton: false,
                        timer: 1200
                    });

                    // Redirect ke riwayat belanja supaya user bisa lihat entry pembelian
                    setTimeout(() => {
                        window.location.href = "{{ route('bengkel.belanja.index') }}";
                    }, 1200);
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
    }
});
</script>
