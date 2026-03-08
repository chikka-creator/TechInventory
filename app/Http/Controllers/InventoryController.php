<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Borrowing;
use App\Models\ItemRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index() {
        if (Auth::user()->role == 'admin') return redirect()->route('admin.dashboard');
        return redirect()->route('user.dashboard');
    }

    // ================== SISWA ==================
    public function userDashboard(Request $request) {
        $query = Item::where('stock', '>', 0);
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')->orWhere('category', 'like', '%' . $request->search . '%');
        }
        $items = $query->get();
        $myBorrowings = Borrowing::where('user_id', Auth::id())->with('item')->latest()->get();
        $myRequests = ItemRequest::where('user_id', Auth::id())->latest()->get();
        $dueBorrowings = Borrowing::where('user_id', Auth::id())->where('status', 'approved')->where('return_date', '<=', now()->toDateString())->with('item')->get();

        $totalPinjam = Borrowing::where('user_id', Auth::id())->count();
        $sedangDipinjam = Borrowing::where('user_id', Auth::id())->whereIn('status', ['pending', 'approved'])->count();

        return view('dashboard_user', compact('items', 'myBorrowings', 'myRequests', 'dueBorrowings', 'totalPinjam', 'sedangDipinjam'));
    }

    public function pinjam(Request $request) {
        $request->validate(['item_id' => 'required', 'return_date' => 'required|date']);
        $item = Item::find($request->item_id);
        if($item->stock < 1) return back()->with('error', 'Stok habis!');
        Borrowing::create([
            'user_id' => Auth::id(), 'item_id' => $request->item_id, 'borrow_date' => now()->toDateString(),
            'return_date' => $request->return_date, 'status' => 'pending'
        ]);
        return back()->with('success', 'Peminjaman diajukan!');
    }

    public function storeRequest(Request $request) {
        if (Auth::user()->penalty_points >= 20) {
            return back()->with('error', 'Akses Ditolak! Poin sanksi Anda mencapai batas (>= 20 Poin).');
        }
        $request->validate(['item_name' => 'required|string', 'reason' => 'required|string']);
        ItemRequest::create([
            'user_id' => Auth::id(), 'item_name' => $request->item_name, 'category' => $request->category,
            'reason' => $request->reason, 'status' => 'pending'
        ]);
        return back()->with('success', 'Permintaan alat dikirim!');
    }

    // ================== ADMIN ==================
    public function adminDashboard() {
        $totalItems = Item::sum('stock');
        $activeBorrowings = Borrowing::where('status', 'approved')->count();
        $pendingRequests = Borrowing::where('status', 'pending')->count();
        $borrowings = Borrowing::with(['user', 'item'])->latest()->get();
        $items = Item::orderBy('name', 'asc')->get();
        $newToolRequests = ItemRequest::with('user')->orderBy('status', 'desc')->latest()->get();

        $problematicUsers = User::where('role', 'user')
            ->where(function($q) {
                $q->where('penalty_points', '>', 0)->orWhere('is_banned', true);
            })->get();

        return view('dashboard_admin', compact(
            'borrowings', 'items', 'totalItems', 'activeBorrowings', 
            'pendingRequests', 'newToolRequests', 'problematicUsers'
        ));
    }

    public function storeItem(Request $request) {
        $existingItem = Item::where('name', $request->name)->first();
        if ($existingItem) {
            $existingItem->increment('stock', $request->stock);
            return back()->with('success', 'Barang sudah ada di gudang. Stok otomatis ditambahkan!');
        }
        Item::create($request->all());
        return back()->with('success', 'Barang baru disimpan di gudang!'); // HIJAU
    }

    public function addStock(Request $request, $id) {
        $item = Item::findOrFail($id);
        $item->increment('stock', $request->jumlah_stok);
        return back()->with('success', 'Stok ' . $item->name . ' berhasil ditambah sebanyak ' . $request->jumlah_stok); // HIJAU
    }

    public function reduceStock(Request $request, $id) {
        $item = Item::findOrFail($id);
        $jumlah = (int) $request->input('jumlah_stok', 0);
        
        if ($item->stock >= $jumlah) {
            $item->decrement('stock', $jumlah);
            // UBAH JADI WARNING (KUNING)
            return back()->with('warning', "Stok {$item->name} berhasil dikurangi sebanyak {$jumlah}."); 
        }
        return back()->with('error', "Gagal: Stok {$item->name} tidak mencukupi untuk dikurangi!"); // MERAH
    }

    public function destroyItem($id) {
        $item = Item::findOrFail($id);
        $adaTransaksi = Borrowing::where('item_id', $id)->exists();
        
        if ($adaTransaksi) {
            $item->update(['stock' => 0]);
            return back()->with('error', "Oops! Alat {$item->name} memiliki riwayat. Stoknya dijadikan 0 untuk mengamankan data.");
        }
        
        $nama = $item->name;
        $item->delete();
        // UBAH JADI DELETE (MERAH)
        return back()->with('delete', "Barang {$nama} berhasil dihapus permanen dari sistem!"); 
    }

    public function approve($id) {
        $b = Borrowing::find($id);
        $item = Item::find($b->item_id);
        if($item->stock > 0) { $item->decrement('stock'); $b->update(['status' => 'approved']); }
        return back()->with('success', 'Peminjaman disetujui!');
    }

    public function reject($id) { Borrowing::find($id)->update(['status' => 'rejected']); return back()->with('warning', 'Peminjaman ditolak.'); }

    public function returnItem(Request $request, $id) {
        $b = Borrowing::findOrFail($id);
        $item = Item::findOrFail($b->item_id);

        $condition = $request->input('condition', 'Baik'); 
        $poin_manual = (int) $request->input('poin_sanksi', 0); 
        $poin_sanksi = 0;

        if ($condition == 'Rusak') { $poin_sanksi = 10; } 
        elseif ($condition == 'Hilang') { $poin_sanksi = 25; }

        if ($poin_manual > 0) { $poin_sanksi = $poin_manual; }
        if ($condition != 'Hilang') { $item->increment('stock'); }

        $b->update(['status' => 'returned', 'condition' => $condition]);
        
        if ($poin_sanksi > 0) {
            $user = $b->user;
            $user->penalty_points += $poin_sanksi;
            if ($user->penalty_points >= 50) { $user->is_banned = true; }
            $user->save();
        }

        return back()->with('success', 'Barang dikembalikan. Siswa mendapat ' . $poin_sanksi . ' Poin Sanksi.');
    }

    public function updateRequestStatus($id, $status) {
        $req = ItemRequest::findOrFail($id);
        if ($status === 'accepted' && $req->status !== 'accepted') {
            $existingItem = Item::where('name', $req->item_name)->first();
            if ($existingItem) { $existingItem->increment('stock'); } 
            else { Item::create(['name' => $req->item_name, 'category' => $req->category ?? 'Lainnya', 'stock' => 1, 'description' => 'Dari request']); }
        }
        $req->update(['status' => $status]);
        return back()->with('success', 'Status permintaan diperbarui.');
    }

    public function reducePoints(Request $request, $id) {
        $user = User::findOrFail($id);
        
        if ($request->has('reset')) {
            $user->penalty_points = 0;
            $user->is_banned = false;
            $user->save();
            return back()->with('success', 'Poin siswa direset menjadi 0 dan akun berhasil di-Unban!');
        }

        $kurangi = (int) $request->input('kurangi_poin', 0);
        if ($kurangi > 0) {
            $user->penalty_points -= $kurangi;
            if ($user->penalty_points < 0) { $user->penalty_points = 0; }
            if ($user->penalty_points < 50) { $user->is_banned = false; }
            $user->save();
            return back()->with('success', "Berhasil mengurangi $kurangi poin dari akun {$user->name}.");
        }
        return back();
    }
}