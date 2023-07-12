<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        return [
            'id' => $this->faker->randomDigit,
            'full_name' => $this->faker->name,
            'DNI' => $this->faker->randomNumber(8),
            'phone' => $this->faker->randomNumber(9),
        ];
    }
}

