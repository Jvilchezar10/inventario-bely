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
            'DNI' => $this->faker->numberBetween(00000001, 99999999),
            'RUC' => $this->faker->numberBetween(10000000000, 99999999999),
            'phone' => $this->faker->numerify('9########'),
            'contact' => $this->faker->name,
            'contact_phone' => $this->faker->numerify('9########'),
        ];
    }
}

