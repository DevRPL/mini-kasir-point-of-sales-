<?php
namespace App\Http\Controllers;

use App\Models\{Product, Transaction};
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function summary()
    {
        $trx   = Transaction::whereDate('created_at', today());
        $omzet = (int) (clone $trx)->sum('total');
        $disc  = (int) (clone $trx)->sum('discount');

        $profit = (int) DB::table('transaction_items as i')
            ->join('transactions as t', 't.id', '=', 'i.transaction_id')
            ->whereDate('t.created_at', today())
            ->sum(DB::raw('(i.price - i.cost) * i.qty')) - $disc;

        $top = DB::table('transaction_items as i')
            ->join('transactions as t', 't.id', '=', 'i.transaction_id')
            ->whereDate('t.created_at', today())
            ->select('i.name', DB::raw('SUM(i.qty) as qty'))
            ->groupBy('i.name')->orderByDesc('qty')->limit(5)->get();

        return [
            'omzet'     => $omzet,
            'trx_count' => (clone $trx)->count(),
            'profit'    => $profit,
            'low_stock' => Product::where('stock', '<=', Product::LOW_STOCK)->count(),
            'top'       => $top,
        ];
    }
}
