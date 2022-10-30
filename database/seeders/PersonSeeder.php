<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Person\Model\PersonDAO;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PersonDAO::factory()
            ->count(50)
            ->create();
    }
}
