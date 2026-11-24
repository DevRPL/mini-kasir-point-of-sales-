<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    const DEFAULT_OPENING = 200000;   // modal awal kas default

    protected $guarded = [];
    protected $casts = ['started_at' => 'datetime', 'closed_at' => 'datetime'];

    public function transactions() { return $this->hasMany(Transaction::class); }

    /** Shift yang sedang berjalan milik user ini (dibuat otomatis bila belum ada). */
    public static function forUser(User $u): self
    {
        return static::whereNull('closed_at')->where('user_id', $u->id)->latest('id')->first()
            ?? static::create(['user_id' => $u->id, 'cashier' => $u->name, 'opening_cash' => self::DEFAULT_OPENING, 'started_at' => now()]);
    }

    public function summary(): array
    {
        $cash = (int) $this->transactions()->where('method', 'Tunai')->sum('total');
        $non  = (int) $this->transactions()->where('method', '!=', 'Tunai')->sum('total');
        return [
            'cash_total'    => $cash,
            'noncash_total' => $non,
            'trx_count'     => $this->transactions()->count(),
            'expected_cash' => (int) $this->opening_cash + $cash,
        ];
    }
}
