<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'        => fake()->name(),
            'is_favorite' => fake()->boolean(),
        ];
    }
}
