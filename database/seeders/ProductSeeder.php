<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Product\Model\ProductDAO;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductDAO::factory()
            ->count(400)
            ->create();
    }
}
