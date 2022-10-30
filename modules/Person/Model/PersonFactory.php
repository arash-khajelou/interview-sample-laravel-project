<?php

namespace Modules\Person\Model;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PersonFactory extends Factory
{
    protected $model = PersonDAO::class;

    public function definition(): array
    {
        return [
            "is_active" => ([true, false])[rand(0, 1)],
            "first_name" => fake()->name(),
            "last_name" => fake()->lastName(),
            "social_id" => "0016789148",
            "birth_date" => fake()->date("Y-m-d"),
            "mobile_number" => fake()->unique()->phoneNumber(),
            "mobile_description" => fake()->realText(50),
            "email" => fake()->unique()->safeEmail(),
            "email_description" => fake()->realText(50),
        ];
    }
}
