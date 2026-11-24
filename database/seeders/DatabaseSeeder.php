<?php
namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UserSeeder::class);
        $rows = [
            ['8999999001','Indomie Goreng','Makanan',2800,3500,80],
            ['8999999002','Beras Premium 5kg','Makanan',60000,68000,15],
            ['8999999003','Roti Tawar Sari Roti','Makanan',13000,16000,8],
            ['8999999004','Aqua 600ml','Minuman',2500,3500,120],
            ['8999999005','Teh Pucuk 350ml','Minuman',3200,4500,60],
            ['8999999006','Kopi Kapal Api Sachet','Minuman',1200,1800,9],
            ['8999999007','Minyak Goreng 1L','Kebutuhan Harian',16000,19500,25],
            ['8999999008','Gula Pasir 1kg','Kebutuhan Harian',14500,17000,4],
            ['8999999009','Tisu Paseo','Kebutuhan Harian',9000,12000,30],
            ['8999999010','Sabun Lifebuoy','Perlengkapan Mandi',3000,4500,40],
            ['8999999011','Shampoo Clear 70ml','Perlengkapan Mandi',8500,11000,22],
            ['8999999012','Pasta Gigi Pepsodent','Perlengkapan Mandi',7000,9500,6],
            ['8999999013','Chitato 68g','Snack',8500,11500,45],
            ['8999999014','Oreo Original','Snack',6000,8500,35],
            ['8999999015','Silverqueen 58g','Snack',11000,15000,20],
        ];
        foreach ($rows as $r) {
            Product::updateOrCreate(['code' => $r[0]], [
                'name' => $r[1], 'category' => $r[2],
                'cost_price' => $r[3], 'price' => $r[4], 'stock' => $r[5],
            ]);
        }
    }
}
