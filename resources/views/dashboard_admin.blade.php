<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lilita+One&family=Nunito:wght@400;600;700&display=swap');
        .font-display { font-family: 'Lilita One', cursive; letter-spacing: 1px; }
        .font-sans { font-family: 'Nunito', sans-serif; }
    </style>

    <div class="py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto font-sans relative">
        
        @if(session('success') || session('error') || session('warning') || session('delete'))
            @php
                $message = '';
                $icon = '';
                $bgClass = '';

                if(session('success')) {
                    $message = session('success');
                    $bgClass = 'bg-[#4ade80] border-[#bbf7d0]'; // HIJAU (Tambah/Sukses)
                } elseif(session('delete')) {
                    $message = session('delete');
                    $bgClass = 'bg-[#ff4d6d] border-[#ffb3c6]'; // MERAH (Hapus)
                } elseif(session('warning')) {
                    $message = session('warning');
                    $bgClass = 'bg-[#fbbf24] border-[#fde68a]'; // KUNING (Kurangi Stok)
                } elseif(session('error')) {
                    $message = session('error');
                    $bgClass = 'bg-[#ff4d6d] border-[#ffb3c6]'; // MERAH (Error/Gagal)
                }
            @endphp

            <div id="adminNotification" 
                 class="fixed top-8 left-1/2 w-max max-w-[90%] {{ $bgClass }} border-4 text-white rounded-full shadow-2xl z-[100] transition-all duration-700 ease-out transform -translate-x-1/2 -translate-y-[250%] flex items-center justify-center gap-4 py-4 px-10">
                
                <span class="text-4xl animate-bounce">{{ $icon }}</span> 
                <span class="font-display text-2xl tracking-wide text-white drop-shadow-md">
                    {{ $message }}
                </span>
                <span class="text-4xl animate-bounce">{{ $icon }}</span>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const notif = document.getElementById('adminNotification');
                    if(notif) {
                        setTimeout(() => {
                            notif.classList.remove('-translate-y-[250%]');
                            notif.classList.add('translate-y-0');
                        }, 200);

                        setTimeout(() => {
                            notif.classList.remove('translate-y-0');
                            notif.classList.add('-translate-y-[250%]');
                        }, 4000);
                    }
                });
            </script>
        @endif
        <div class="mb-8 border-b-4 border-[#635bff] pb-4 inline-block mt-4">
            <h2 class="font-display text-4xl text-[#635bff] tracking-wide">Panel Admin Laboratorium</h2>
            <p class="text-gray-500 font-bold">Kelola stok, persetujuan pinjaman, request alat, dan sanksi siswa.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-[#ffd6f4] p-6 rounded-[2rem] shadow-sm text-black transform hover:-translate-y-1 transition duration-300 text-center">
                <p class="font-bold text-sm uppercase opacity-70 tracking-wider">Total Fisik di Gudang</p>
                <h3 class="font-display text-5xl mt-2">{{ $totalItems }} <span class="text-2xl">Unit</span></h3>
            </div>
            <div class="bg-[#838fff] p-6 rounded-[2rem] shadow-sm text-white transform hover:-translate-y-1 transition duration-300 text-center">
                <p class="font-bold text-sm uppercase opacity-80 tracking-wider">Peminjaman Aktif</p>
                <h3 class="font-display text-5xl mt-2">{{ $activeBorrowings }} <span class="text-2xl">Transaksi</span></h3>
            </div>
            <div class="bg-[#635bff] p-6 rounded-[2rem] shadow-sm text-white transform hover:-translate-y-1 transition duration-300 text-center">
                <p class="font-bold text-sm uppercase opacity-80 tracking-wider">Menunggu Persetujuan</p>
                <h3 class="font-display text-5xl mt-2 text-[#ffd6f4]">{{ $pendingRequests }} <span class="text-2xl text-white opacity-70">Antrean</span></h3>
            </div>
        </div>

        <div class="bg-white p-6 md:p-8 rounded-[3rem] shadow-sm border-4 border-[#d2c4ff] mb-10">
            <h3 class="font-display text-4xl mb-2 text-[#635bff] tracking-wide text-center">Manajemen Stok Gudang</h3>
            <p class="text-sm text-gray-500 font-bold mb-8 text-center">Tambah, kurangi, atau hapus alat dari sistem inventaris secara langsung.</p>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-[#f4f4f9] text-[#635bff] font-display text-lg">
                        <tr>
                            <th class="py-3 px-6 rounded-tl-full">Nama Alat</th>
                            <th class="py-3 px-4 text-center">Sisa Stok</th>
                            <th class="py-3 px-6 rounded-tr-full text-center">Aksi (Tambah / Kurang / Hapus)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="py-4 px-6">
                                <p class="font-bold text-gray-800 text-lg">{{ $item->name }}</p>
                                <span class="text-[10px] font-bold bg-gray-200 text-gray-600 px-3 py-1 rounded-full">{{ $item->category }}</span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <span class="font-display text-3xl {{ $item->stock > 0 ? 'text-[#838fff]' : 'text-red-500' }}">{{ $item->stock }}</span>
                            </td>
                            <td class="py-4 px-6 flex justify-center gap-4">
                                
                                <form method="POST" class="flex gap-2 items-center bg-gray-100 p-2 rounded-full border border-gray-200">
                                    @csrf
                                    <input type="number" name="jumlah_stok" placeholder="Jml" class="w-20 text-sm border-none bg-white rounded-full py-2 px-3 text-black focus:ring-2 focus:ring-[#838fff] shadow-inner text-center" required min="1">
                                    <button type="submit" formaction="{{ route('admin.item.addStock', $item->id) }}" class="bg-[#838fff] text-white px-5 py-2 rounded-full text-xl font-display hover:bg-[#635bff] transition shadow-sm drop-shadow-sm" title="Tambah Stok">+</button>
                                    <button type="submit" formaction="{{ route('admin.item.reduceStock', $item->id) }}" class="bg-red-400 text-white px-5 py-2 rounded-full text-xl font-display hover:bg-red-500 transition shadow-sm drop-shadow-sm" title="Kurangi Stok">-</button>
                                </form>

                                <form action="{{ route('admin.item.destroy', $item->id) }}" method="POST" class="flex items-center">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin ingin menghapus {{ $item->name }} dari sistem?')" 
                                            class="bg-red-400 text-white px-6 py-3 rounded-full font-display text-lg flex items-center justify-center hover:bg-red-500 transition shadow-sm drop-shadow-sm" title="Hapus Alat">
                                        Hapus Alat
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @endforeach
                        @if($items->isEmpty())
                        <tr><td colspan="3" class="text-center py-8 font-bold text-gray-400">Belum ada barang di gudang. Silakan tambah inventaris baru.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
            <div class="bg-white p-6 md:p-8 rounded-[3rem] shadow-sm border-4 border-[#ffd6f4] h-fit">
                <h3 class="font-display text-2xl mb-2 text-[#635bff] text-center">Tambah Alat</h3>
                <p class="text-xs text-gray-500 font-bold mb-6 text-center">Daftarkan alat yang baru dibeli.</p>
                
                <form action="{{ route('admin.item.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase ml-4 mb-1 block">Nama Alat</label>
                        <input type="text" name="name" placeholder="Cth: Multitester" class="w-full border-none bg-gray-100 rounded-full px-5 py-3 text-sm focus:ring-4 focus:ring-[#ffd6f4]" required>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase ml-4 mb-1 block">Kategori</label>
                        <select name="category" class="w-full border-none bg-gray-100 rounded-full px-5 py-3 text-sm focus:ring-4 focus:ring-[#ffd6f4]">
                            <option value="Mikrokontroler">Mikrokontroler</option>
                            <option value="Sensor">Sensor</option>
                            <option value="Alat Ukur">Alat Ukur</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase ml-4 mb-1 block">Stok Awal</label>
                        <input type="number" name="stock" placeholder="Jumlah" class="w-full border-none bg-gray-100 rounded-full px-5 py-3 text-sm focus:ring-4 focus:ring-[#ffd6f4]" required min="1">
                    </div>
                    <button type="submit" class="w-full bg-[#635bff] text-white text-xl py-3 rounded-full font-display hover:scale-105 transition shadow-sm drop-shadow-sm mt-2">Simpan Ke Gudang</button>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white p-6 md:p-8 rounded-[3rem] shadow-sm border-4 border-[#838fff]">
                <h3 class="font-display text-2xl text-[#635bff] mb-6">Proses Peminjaman Masuk</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-[#f4f4f9] text-[#635bff] font-display text-lg">
                            <tr>
                                <th class="py-3 px-4 rounded-tl-full">Siswa</th>
                                <th class="py-3 px-4">Alat & Deadline</th>
                                <th class="py-3 px-4 rounded-tr-full">Aksi Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($borrowings as $log)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-4 px-4">
                                    <p class="font-bold text-gray-800">{{ $log->user->name }}</p>
                                    <span class="text-[10px] font-bold px-3 py-1 rounded-full {{ $log->user->penalty_points > 0 ? 'bg-red-100 text-red-600' : 'bg-gray-200 text-gray-600' }}">Poin Sanksi: {{ $log->user->penalty_points }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="font-bold text-[#635bff]">{{ $log->item->name }}</p>
                                    <p class="text-[11px] font-bold text-red-500 tracking-wide">Kembali: {{ \Carbon\Carbon::parse($log->return_date)->format('d M Y') }}</p>
                                </td>
                                <td class="py-4 px-4">
                                    @if($log->status == 'pending')
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.approve', $log->id) }}" class="bg-[#4ade80] text-white px-5 py-2 rounded-full text-sm font-bold hover:bg-green-500 transition shadow-sm">Terima</a>
                                            <a href="{{ route('admin.reject', $log->id) }}" class="bg-red-400 text-white px-5 py-2 rounded-full text-sm font-bold hover:bg-red-500 transition shadow-sm">Tolak</a>
                                        </div>
                                    @elseif($log->status == 'approved')
                                        <form action="{{ route('admin.return', $log->id) }}" method="POST" class="flex gap-2 items-center bg-gray-100 p-2 rounded-full w-max">
                                            @csrf
                                            <select name="condition" class="text-xs border-none bg-white rounded-full py-2 px-3 shadow-sm focus:ring-2 focus:ring-[#838fff]">
                                                <option value="Baik">Baik</option>
                                                <option value="Rusak">Rusak</option>
                                                <option value="Hilang">Hilang</option>
                                            </select>
                                            <input type="number" name="poin_sanksi" placeholder="Poin(0)" class="w-20 text-xs border-none bg-white rounded-full py-2 shadow-sm focus:ring-2 focus:ring-[#838fff] text-center" min="0" value="0">
                                            <button type="submit" class="bg-[#ffd6f4] text-black px-5 py-2 rounded-full text-sm font-display hover:bg-white transition shadow-sm drop-shadow-sm">Return</button>
                                        </form>
                                    @else
                                        <span class="px-5 py-2 rounded-full text-[10px] uppercase font-bold bg-gray-200 text-gray-500">Selesai ({{ $log->condition ?? '-' }})</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 md:p-8 rounded-[3rem] shadow-sm border-4 border-[#635bff] mb-10">
            <h3 class="font-display text-3xl text-[#635bff] mb-2"> Request Pengadaan Alat</h3>
            <p class="text-sm text-gray-500 font-bold mb-6">Klik "ACC (Beli)" dan sistem otomatis menambahkannya ke gudang.</p>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-[#f4f4f9] text-[#635bff] font-display text-lg">
                        <tr>
                            <th class="py-3 px-6 rounded-tl-full">Nama Siswa</th>
                            <th class="py-3 px-4">Alat & Kategori</th>
                            <th class="py-3 px-4">Alasan Kebutuhan</th>
                            <th class="py-3 px-6 rounded-tr-full">Aksi Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($newToolRequests as $req)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="py-4 px-6 font-bold text-gray-800">{{ $req->user->name }}</td>
                            <td class="py-4 px-4">
                                <p class="font-bold text-[#838fff] text-base">{{ $req->item_name }}</p>
                                <span class="text-[10px] font-bold bg-gray-200 text-gray-600 px-3 py-1 rounded-full">{{ $req->category }}</span>
                            </td>
                            <td class="py-4 px-4 text-gray-600 font-medium">"{{ $req->reason }}"</td>
                            <td class="py-4 px-6">
                                @if($req->status == 'pending')
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.request.update', ['id' => $req->id, 'status' => 'accepted']) }}" class="bg-[#838fff] text-white px-5 py-2 rounded-full text-sm font-bold hover:bg-[#635bff] transition shadow-sm">✔ ACC</a>
                                        <a href="{{ route('admin.request.update', ['id' => $req->id, 'status' => 'rejected']) }}" class="bg-gray-300 text-gray-700 px-5 py-2 rounded-full text-sm font-bold hover:bg-gray-400 transition shadow-sm">✖ Tolak</a>
                                    </div>
                                @else
                                    <span class="px-5 py-2 rounded-full text-[10px] uppercase font-bold text-white {{ $req->status == 'accepted' ? 'bg-[#4ade80]' : 'bg-[#ff4d6d]' }}">
                                        {{ $req->status }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white p-6 md:p-8 rounded-[3rem] shadow-sm border-4 border-[#ffb3c6]">
            <h3 class="font-display text-3xl text-[#ff4d6d] mb-2">Manajemen Sanksi & Unban Siswa</h3>
            <p class="text-sm text-gray-500 font-bold mb-6">Kurangi poin pelanggaran siswa atau buka blokir (Unban).</p>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-[#fff0f3] text-[#ff4d6d] font-display text-lg">
                        <tr>
                            <th class="py-3 px-6 rounded-tl-full">Nama Siswa</th>
                            <th class="py-3 px-4 text-center">Total Poin</th>
                            <th class="py-3 px-4 text-center">Status Akun</th>
                            <th class="py-3 px-6 rounded-tr-full">Aksi Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($problematicUsers as $siswa)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="py-4 px-6 font-bold text-gray-800 text-base">{{ $siswa->name }}</td>
                            <td class="py-4 px-4 text-center">
                                <span class="font-display text-3xl {{ $siswa->penalty_points >= 50 ? 'text-[#ff4d6d]' : ($siswa->penalty_points >= 20 ? 'text-[#fbbf24]' : 'text-yellow-500') }}">{{ $siswa->penalty_points }}</span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($siswa->is_banned)
                                    <span class="px-4 py-2 rounded-full text-[10px] uppercase font-bold bg-[#ff4d6d] text-white animate-pulse">BANNED</span>
                                @elseif($siswa->penalty_points >= 20)
                                    <span class="px-4 py-2 rounded-full text-[10px] uppercase font-bold bg-[#fbbf24] text-white">REQ DIBLOKIR</span>
                                @else
                                    <span class="px-4 py-2 rounded-full text-[10px] uppercase font-bold bg-[#838fff] text-white">AKTIF</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <form action="{{ route('admin.user.reducePoints', $siswa->id) }}" method="POST" class="flex gap-2 items-center bg-gray-50 p-2 rounded-full w-max border border-gray-100">
                                    @csrf
                                    <input type="number" name="kurangi_poin" placeholder="-Poin" class="w-20 text-sm border-none bg-white rounded-full py-2 px-3 shadow-inner focus:ring-2 focus:ring-[#ffb3c6] text-center" min="1" max="{{ $siswa->penalty_points }}">
                                    <button type="submit" class="bg-[#fbbf24] text-white px-5 py-2 rounded-full text-sm font-bold hover:bg-[#d97706] transition shadow-sm drop-shadow-sm">Kurangi</button>
                                    <button type="submit" name="reset" value="1" class="bg-[#4ade80] text-white px-5 py-2 rounded-full text-sm font-bold hover:bg-green-500 transition shadow-sm drop-shadow-sm">Reset & Unban</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-8 text-center font-display text-2xl text-gray-400">Yeay! Semua siswa disiplin.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>