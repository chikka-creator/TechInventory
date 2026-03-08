<x-app-layout>
    <x-slot name="header"><h2>Dashboard Admin</h2></x-slot>

    <div class="py-12 px-6">
        <h3 class="text-xl font-bold">Request Pending</h3>
        <table class="w-full bg-white shadow-md rounded mb-8">
            <thead><tr><th>Siswa</th><th>Alat</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($pending as $p)
                <tr class="border-b">
                    <td class="p-2">{{ $p->user->name }}</td>
                    <td class="p-2">{{ $p->item->name }}</td>
                    <td class="p-2 flex gap-2">
                        <form action="{{ route('borrow.approve', $p->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="bg-green-500 text-white px-3 py-1 rounded">Terima</button>
                        </form>
                        <form action="{{ route('borrow.reject', $p->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="bg-red-500 text-white px-3 py-1 rounded">Tolak</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h3 class="text-xl font-bold">Barang Sedang Dpinjam (Perlu Dikembalikan)</h3>
        <table class="w-full bg-white shadow-md rounded">
            <thead><tr><th>Siswa</th><th>Alat</th><th>Tgl Kembali</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($active as $a)
                <tr class="border-b">
                    <td class="p-2">{{ $a->user->name }}</td>
                    <td class="p-2">{{ $a->item->name }}</td>
                    <td class="p-2">{{ $a->return_date }}</td>
                    <td class="p-2">
                        <form action="{{ route('borrow.return', $a->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="text" name="note" placeholder="Kondisi barang..." class="border text-sm">
                            <button class="bg-blue-500 text-white px-3 py-1 rounded">Selesai (Return)</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>