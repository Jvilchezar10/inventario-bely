<?php

namespace Database\Factories;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProviderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Provider::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'provider' => $this->faker->company,
            'DNI' => $this->faker->randomNumber(8),
            'RUC' => $this->faker->randomNumber(11),
            'phone' => $this->faker->numerify('9########'),
            'contact' => $this->faker->name,
            'contact_phone' => $this->faker->numerify('9########'),
        ];
    }
}

