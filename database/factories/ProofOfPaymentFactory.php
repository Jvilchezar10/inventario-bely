<?php

namespace Database\Factories;

use App\Models\ProofOfPayment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProofOfPaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProofOfPayment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'state' => $this->faker->randomElement(['descontinuado', 'vigente']),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
