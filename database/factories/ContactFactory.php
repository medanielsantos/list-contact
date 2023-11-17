<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    public function definition(): array
    {
        return [
            'person_id'   => rand(1, Person::query()->count()),
            'email'       => $this->faker->unique()->safeEmail(),
            'phone'       => $this->faker->phoneNumber(),
            'whatsapp'    => $this->faker->phoneNumber(),
            'is_favorite' => $this->faker->boolean(),
        ];
    }
}
