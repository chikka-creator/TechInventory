namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->is_admin) {
            // DATA UNTUK ADMIN
            $pending = Borrowing::where('status', 'pending')->with(['user', 'item'])->get();
            $active = Borrowing::where('status', 'approved')->with(['user', 'item'])->get();
            return view('admin.dashboard', compact('pending', 'active'));
        } else {
            // DATA UNTUK USER
            $items = Item::where('stock', '>', 0)->get();
            $myBorrowings = Borrowing::where('user_id', Auth::id())->with('item')->latest()->get();
            return view('user.dashboard', compact('items', 'myBorrowings'));
        }
    }
}