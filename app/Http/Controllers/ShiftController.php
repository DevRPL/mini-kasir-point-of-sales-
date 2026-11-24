<?php
namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /** Shift milik user yang login. */
    public function current(Request $r)
    {
        $s = Shift::forUser($r->user());
        return array_merge($s->toArray(), $s->summary());
    }

    public function close(Request $r)
    {
        $d = $r->validate(['counted_cash' => 'required|integer|min:0', 'next_opening' => 'required|integer|min:0']);
        $s = Shift::forUser($r->user());
        $sum = $s->summary();

        $s->update($sum + [
            'closed_at'    => now(),
            'closing_cash' => $d['counted_cash'],
            'difference'   => $d['counted_cash'] - $sum['expected_cash'],
        ]);
        Shift::create(['user_id' => $s->user_id, 'cashier' => $s->cashier, 'opening_cash' => $d['next_opening'], 'started_at' => now()]);
        return response()->json(['ok' => true]);
    }

    /** Khusus admin: riwayat semua shift. */
    public function history()
    {
        return Shift::whereNotNull('closed_at')->latest('id')->limit(500)->get();
    }
}
