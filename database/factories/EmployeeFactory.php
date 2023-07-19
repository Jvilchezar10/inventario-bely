<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->randomDigit,
            'cod_emp' => $this->faker->unique()->numerify('EMP####'),
            'name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'phone' => $this->faker->numerify('9########'),
            'email' => $this->faker->unique()->safeEmail,
            'state' => $this->faker->randomElement(['retirado', 'vigente']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
