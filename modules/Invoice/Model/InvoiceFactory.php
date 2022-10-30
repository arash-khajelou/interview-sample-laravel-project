<?php

namespace Modules\Invoice\Model;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InvoiceFactory extends Factory
{
    protected $model = InvoiceDAO::class;

    public function definition(): array
    {
        return [
            "person_id" => rand(1, 30),
            "total_sum" => 0
        ];
    }
}
