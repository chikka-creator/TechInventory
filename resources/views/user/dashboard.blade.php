<x-app-layout>
    <x-slot name="header"><h2>Dashboard Siswa</h2></x-slot>

    <div class="py-12 px-6">
        <h3>Katalog Alat</h3>
        <div class="grid grid-cols-3 gap-4">
            @foreach($items as $item)
            <div class="border p-4 bg-white rounded">
                <h4>{{ $item->name }}</h4>
                <p>Stok: {{ $item->stock }}</p>
                <form action="{{ route('borrow.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                    <label>Tgl Kembali:</label>
                    <input type="date" name="return_date" required class="border rounded">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-2">Pinjam</button>
                </form>
            </div>
            @endforeach
        </div>

        <h3 class="mt-8">Riwayat Peminjaman Saya</h3>
        <table class="w-full bg-white mt-2">
            <thead>
                <tr><th>Alat</th><th>Status</th><th>Tgl Kembali</th></tr>
            </thead>
            <tbody>
                @foreach($myBorrowings as $log)
                <tr>
                    <td>{{ $log->item->name }}</td>
                    <td>
                        <span class="badge {{ $log->status == 'approved' ? 'bg-green-200' : 'bg-yellow-200' }}">
                            {{ strtoupper($log->status) }}
                        </span>
                    </td>
                    <td>{{ $log->return_date }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>