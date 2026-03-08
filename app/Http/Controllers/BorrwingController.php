namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    // USER: Mengajukan Pinjaman
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'return_date' => 'required|date|after:today',
        ]);

        // Cek stok lagi untuk keamanan
        $item = Item::find($request->item_id);
        if($item->stock < 1) return back()->with('error', 'Stok habis!');

        Borrowing::create([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id,
            'borrow_date' => now(),
            'return_date' => $request->return_date,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Pengajuan berhasil, tunggu admin!');
    }

    // ADMIN: Setujui Pinjaman
    public function approve($id)
    {
        // Gunakan Transaction agar data konsisten
        DB::transaction(function () use ($id) {
            $borrowing = Borrowing::findOrFail($id);
            $item = Item::findOrFail($borrowing->item_id);

            if ($item->stock > 0) {
                // Kurangi Stok
                $item->decrement('stock');
                // Update Status
                $borrowing->update(['status' => 'approved']);
            }
        });

        return back()->with('success', 'Peminjaman disetujui');
    }

    // ADMIN: Tolak Pinjaman
    public function reject($id)
    {
        Borrowing::where('id', $id)->update(['status' => 'rejected']);
        return back()->with('success', 'Peminjaman ditolak');
    }

    // ADMIN: Konfirmasi Pengembalian
    public function returnItem(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            $borrowing = Borrowing::findOrFail($id);
            $item = Item::findOrFail($borrowing->item_id);

            // Tambah Stok Kembali
            $item->increment('stock');
            
            // Simpan status
            $borrowing->update([
                'status' => 'returned',
                'admin_note' => $request->note // misal: "Barang aman" atau "Lecet dikit"
            ]);
        });

        return back()->with('success', 'Barang telah dikembalikan');
    }
}