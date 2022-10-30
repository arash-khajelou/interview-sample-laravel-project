<?php

namespace Modules\Product\Model;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = ProductDAO::class;

    public function definition()
    {
        return [
            "name" => "Product " . fake()->colorName(),
            "is_active" => true,
            "sell_price" => fake()->randomNumber(6),
            "tax_percentage" => ([3, 6, 9])[rand(0, 2)],
            "discount_percentage" => ([3, 5, 10, 15])[rand(0, 3)],
            "inventory" => 100.0
        ];
    }
}
