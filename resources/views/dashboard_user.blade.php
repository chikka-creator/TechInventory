<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lilita+One&family=Nunito:wght@400;600;700&display=swap');
        .font-display { font-family: 'Lilita One', cursive; letter-spacing: 1px; }
        .font-sans { font-family: 'Nunito', sans-serif; }
    </style>

    <div class="py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto font-sans">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-[#ffd6f4] p-6 rounded-[2rem] shadow-md text-black transform hover:-translate-y-1 transition duration-300">
                <p class="font-bold text-sm uppercase opacity-70 tracking-wider">Sedang Dipinjam</p>
                <h3 class="font-display text-5xl mt-2">{{ $sedangDipinjam }} <span class="text-2xl">Alat</span></h3>
            </div>
            <div class="bg-[#838fff] p-6 rounded-[2rem] shadow-md text-white transform hover:-translate-y-1 transition duration-300">
                <p class="font-bold text-sm uppercase opacity-80 tracking-wider">Total Pinjam</p>
                <h3 class="font-display text-5xl mt-2">{{ $totalPinjam }} <span class="text-2xl">Kali</span></h3>
            </div>
            <div class="bg-[#635bff] p-6 rounded-[2rem] shadow-md text-white transform hover:-translate-y-1 transition duration-300 border-4 {{ Auth::user()->penalty_points >= 20 ? 'border-red-400' : 'border-transparent' }}">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-bold text-sm uppercase opacity-80 tracking-wider">Poin Sanksi</p>
                        <h3 class="font-display text-5xl mt-2 {{ Auth::user()->penalty_points >= 20 ? 'text-red-300' : 'text-white' }}">
                            {{ Auth::user()->penalty_points }}<span class="text-2xl opacity-70">/50</span>
                        </h3>
                    </div>
                    <div class="text-[10px] text-right opacity-70 leading-tight bg-black/20 p-2 rounded-xl">
                        <p>20 = Request Blokir</p>
                        <p>50 = Akun Banned</p>
                    </div>
                </div>
            </div>
        </div>

        @if($dueBorrowings->count() > 0)
        <div class="bg-red-400 text-white p-6 mb-10 rounded-[2rem] shadow-lg animate-pulse border-4 border-red-500">
            <h3 class="font-display text-2xl mb-2 flex items-center gap-2">
                PERINGATAN JATUH TEMPO!
            </h3>
            <p class="text-sm font-bold mb-2 opacity-90">Segera kembalikan alat berikut agar tidak terkena sanksi poin:</p>
            <ul class="list-disc ml-8 text-sm font-bold bg-black/10 inline-block p-4 rounded-2xl">
                @foreach($dueBorrowings as $due)
                    <li>{{ $due->item->name }} (Harus kembali: {{ \Carbon\Carbon::parse($due->return_date)->format('d F Y') }})</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="mb-10 max-w-2xl mx-auto">
            <form action="{{ route('user.dashboard') }}" method="GET" class="flex gap-2 bg-[#635bff] p-3 rounded-full shadow-lg">
                <input type="text" name="search" placeholder="Cari alat di katalog (Cth: ESP32)..." value="{{ request('search') }}"
                       class="w-full border-none rounded-full px-6 py-3 text-black font-bold focus:ring-4 focus:ring-[#ffd6f4] shadow-inner">
                <button type="submit" class="bg-[#ffd6f4] text-black font-display text-xl px-8 py-2 rounded-full hover:bg-white transition shadow-md">
                    Cari
                </button>
            </form>
        </div>

        <div class="bg-[#d2c4ff] p-6 md:p-8 rounded-[2rem] shadow-md mb-10">
            <h3 class="font-display text-3xl mb-6 text-black tracking-wide pl-2">Alat yang tersedia</h3>
            
            @if($items->isEmpty())
                <div class="bg-white/50 p-8 rounded-[2rem] text-center">
                    <p class="text-black font-bold text-lg">Alat tidak ditemukan atau stok sedang kosong.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($items as $item)
                    <div class="bg-[#8494FF] p-5 rounded-[2rem] shadow-sm flex flex-col text-white transition-transform duration-300 hover:scale-105 hover:shadow-xl border-2 border-transparent hover:border-white/50">
                        <div class="mb-3">
                            <h4 class="font-display text-2xl leading-tight mb-2 truncate" title="{{ $item->name }}">{{ $item->name }}</h4>
                            <span class="text-xs font-bold bg-white/30 text-white px-3 py-1.5 rounded-full inline-block">{{ $item->category }}</span>
                        </div>
                        <p class="text-sm text-white/90 mb-4 flex-grow line-clamp-2">{{ $item->description ?? 'Tidak ada deskripsi alat.' }}</p>
                        <div class="mt-auto bg-black/10 p-4 rounded-[1.5rem]">
                            <p class="text-sm font-bold mb-2 {{ $item->stock > 0 ? 'text-[#ffd6f4]' : 'text-red-300' }}">Sisa Stok: {{ $item->stock }}</p>
                            <form action="{{ route('pinjam') }}" method="POST">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                <label class="text-[10px] font-bold text-white uppercase tracking-wider pl-1 mb-1 block">Tgl Kembali:</label>
                                <input type="date" name="return_date" required class="text-xs border-none rounded-xl w-full mb-3 text-black focus:ring-4 focus:ring-[#ffd6f4] shadow-inner bg-white">
                                <button type="submit" class="w-full bg-[#ffd6f4] text-black text-xl py-2 rounded-xl font-display hover:bg-white transition shadow-md">Pinjam</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-[#635bff] p-6 md:p-8 rounded-[2rem] shadow-md mb-10 text-white border-4 border-[#635bff]">
            <h3 class="font-display text-3xl mb-2 tracking-wide text-white">Request Alat Baru</h3>
            <p class="text-sm mb-6 opacity-80 font-bold">Tidak menemukan alat yang kamu butuhkan? Ajukan ke Admin!</p>
            
            @if(Auth::user()->penalty_points >= 20)
                <div class="bg-red-400 p-6 rounded-[2rem] text-white font-bold shadow-inner">
                    <p class="font-display text-2xl mb-1">Akses Diblokir!</p>
                    <p>Kamu tidak dapat me-request alat karena Poin Sanksi ({{ Auth::user()->penalty_points }} Poin) mencapai batas maksimal.</p>
                </div>
            @else
                <form action="{{ route('request.store') }}" method="POST" class="bg-white p-6 rounded-[2rem] shadow-inner text-black">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-2 mb-1 block">Nama Alat</label>
                            <input type="text" name="item_name" placeholder="Cth: Raspberry Pi" required class="w-full border-none bg-gray-100 rounded-2xl px-5 py-3 text-sm focus:ring-4 focus:ring-[#838fff]">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-2 mb-1 block">Kategori</label>
                            <select name="category" class="w-full border-none bg-gray-100 rounded-2xl px-5 py-3 text-sm focus:ring-4 focus:ring-[#838fff]">
                                <option value="Mikrokontroler">Mikrokontroler</option>
                                <option value="Sensor">Sensor</option>
                                <option value="Alat Ukur">Alat Ukur</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="text-xs font-bold text-gray-500 uppercase ml-2 mb-1 block">Alasan Kebutuhan</label>
                        <textarea name="reason" rows="2" placeholder="Untuk pengerjaan tugas akhir IoT..." required class="w-full border-none bg-gray-100 rounded-2xl px-5 py-3 text-sm focus:ring-4 focus:ring-[#838fff]"></textarea>
                    </div>
                    <button type="submit" class="bg-[#838fff] text-white text-xl px-8 py-3 rounded-2xl font-display hover:bg-[#635bff] transition shadow-md w-full md:w-auto">
                        Kirim Request
                    </button>
                </form>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-md border-4 border-[#d2c4ff]">
                <h3 class="font-display text-2xl text-[#635bff] mb-6">Riwayat Pinjam</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <tbody>
                            @foreach($myBorrowings as $log)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-4 font-bold text-gray-800">{{ $log->item->name }}</td>
                                <td class="py-4 text-xs font-bold opacity-60">{{ \Carbon\Carbon::parse($log->return_date)->format('d M y') }}</td>
                                <td class="py-4 text-right">
                                    <span class="px-3 py-1.5 rounded-full text-[10px] uppercase font-bold text-white
                                        {{ $log->status == 'pending' ? 'bg-yellow-400' : ($log->status == 'approved' ? 'bg-blue-400' : ($log->status == 'returned' ? 'bg-green-400' : 'bg-red-400')) }}">
                                        {{ $log->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-md border-4 border-[#ffd6f4]">
                <h3 class="font-display text-2xl text-[#635bff] mb-6">Status Request</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <tbody>
                            @foreach($myRequests as $req)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-4 font-bold text-gray-800">{{ $req->item_name }}</td>
                                <td class="py-4 text-right">
                                    <span class="px-3 py-1.5 rounded-full text-[10px] uppercase font-bold text-white
                                        {{ $req->status == 'accepted' ? 'bg-green-400' : ($req->status == 'rejected' ? 'bg-red-400' : 'bg-yellow-400') }}">
                                        {{ $req->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>