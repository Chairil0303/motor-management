<div>
    <table class="w-full border border-gray-300 text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">#</th>
                <th class="p-2 border">Deskripsi</th>
                <th class="p-2 border">Tanggal Restorasi</th>
                <th class="p-2 border">Biaya</th>
                <th class="p-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($motor->restorasis as $r)
                <tr>
                    <td class="p-2 border">{{ $loop->iteration }}</td>
                    <td class="p-2 border">{{ $r->deskripsi ?? '-' }}</td>
                    <td class="p-2 border">{{ $r->tanggal_restorasi }}</td>
                    <td class="p-2 border">Rp {{ number_format($r->biaya_restorasi, 0, ',', '.') }}</td>
                    <td class="p-2 border flex gap-2">
                        <button
                            onclick="openEditModal({{ $r->id }}, '{{ $r->deskripsi }}', '{{ $r->tanggal_restorasi }}', '{{ $r->biaya_restorasi }}')"
                            class="text-blue-600 hover:underline">
                            Edit
                        </button>
                        <button onclick="confirmDelete({{ $r->id }})" class="text-red-600 hover:underline">
                            Hapus
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">
                        Belum ada data restorasi untuk motor ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if ($motor->restorasis->count() > 0)
            <tfoot>
                <tr class="font-semibold bg-gray-50">
                    <td colspan="3" class="p-2 border text-right">Total</td>
                    <td colspan="2" class="p-2 border">
                        Rp {{ number_format($motor->restorasis->sum('biaya_restorasi'), 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>
</div>