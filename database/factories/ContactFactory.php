<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    public function definition(): array
    {
        $type = $this->faker->randomElement(['email', 'phone', 'whatsapp']);

        switch ($type) {
            case 'email':
                $data = $this->faker->unique()->email;

                break;
            case 'phone':
                $data = $this->faker->unique()->phoneNumber;

                break;
            case 'whatsapp':
                $data = $this->faker->unique()->e164PhoneNumber;

                break;
            default:
                $data = null;
        }

        return [
            'person_id'   => rand(1, Person::query()->count()),
            'type'        => $type,
            'value'       => $data,
            'is_favorite' => $this->faker->boolean(),
        ];
    }
}
