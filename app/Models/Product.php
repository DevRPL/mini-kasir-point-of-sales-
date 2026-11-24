<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const CATEGORIES = ['Makanan', 'Minuman', 'Kebutuhan Harian', 'Perlengkapan Mandi', 'Snack'];
    const LOW_STOCK = 10;

    protected $guarded = [];
    protected $casts = ['cost_price' => 'integer', 'price' => 'integer', 'stock' => 'integer'];
}
