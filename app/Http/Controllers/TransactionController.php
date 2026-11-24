<?php
namespace App\Http\Controllers;

use App\Models\{Product, Shift, Transaction};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /** Transaksi hari ini. */
    public function index(Request $request)
    {
        $q = Transaction::with('items')->whereDate('created_at', today())->latest('id');
        if (!$request->user()->isAdmin()) $q->where('user_id', $request->user()->id);   // kasir: hanya transaksinya sendiri
        return $q->get();
    }

    /** Simpan transaksi; harga, diskon, pajak & stok dihitung ulang di server. */
    public function store(Request $request)
    {
        $d = $request->validate([
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|integer|exists:products,id',
            'items.*.qty'          => 'required|integer|min:1',
            'discount_type'        => 'required|in:%,Rp',
            'discount_value'       => 'nullable|numeric|min:0',
            'ppn'                  => 'boolean',
            'method'               => 'required|in:Tunai,QRIS,Kartu',
            'paid'                 => 'nullable|integer|min:0',
        ]);

        return DB::transaction(function () use ($d, $request) {
            $groups   = collect($d['items'])->groupBy('product_id');
            $products = Product::whereIn('id', $groups->keys())->lockForUpdate()->get()->keyBy('id');

            $sub = 0; $lines = [];
            foreach ($groups as $id => $g) {
                $p = $products[$id]; $qty = (int) $g->sum('qty');
                if ($p->stock < $qty) abort(422, "Stok {$p->name} tidak cukup (sisa {$p->stock}).");
                $sub += $p->price * $qty;
                $lines[] = ['product_id' => $id, 'name' => $p->name, 'price' => $p->price, 'cost' => $p->cost_price, 'qty' => $qty];
            }

            $v    = (float) ($d['discount_value'] ?? 0);
            $disc = $d['discount_type'] === '%' ? $sub * min($v, 100) / 100 : $v;
            $disc = (int) round(min($disc, $sub));
            $tax  = !empty($d['ppn']) ? (int) round(($sub - $disc) * 0.11) : 0;
            $total = $sub - $disc + $tax;
            $paid  = $d['method'] === 'Tunai' ? (int) ($d['paid'] ?? 0) : $total;
            if ($paid < $total) abort(422, 'Uang yang diterima kurang dari total.');

            $shift = Shift::forUser($request->user());
            $n = Transaction::whereDate('created_at', today())->lockForUpdate()->count() + 1;

            $trx = Transaction::create([
                'invoice'       => 'INV-' . now()->format('Ymd') . '-' . str_pad($n, 4, '0', STR_PAD_LEFT),
                'shift_id'      => $shift->id,
                'user_id'       => $request->user()->id,
                'cashier'       => $request->user()->name,
                'subtotal'      => $sub, 'discount' => $disc, 'tax' => $tax, 'total' => $total,
                'method'        => $d['method'], 'paid' => $paid, 'change_amount' => $paid - $total,
            ]);

            foreach ($lines as $l) {
                $trx->items()->create($l);
                $products[$l['product_id']]->decrement('stock', $l['qty']);   // stok otomatis berkurang
            }
            return response()->json($trx->load('items'), 201);
        });
    }
}
