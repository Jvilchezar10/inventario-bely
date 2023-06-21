<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
// use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = Category::all()->pluck('id');

        return [
            'category_id' => $this->faker->randomElement($categories),
            'cod_product' => $this->faker->unique()->ean13,
            'desc' => $this->faker->sentence,
            'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL']),
            'stock_min' => $this->faker->numberBetween(1, 10),
            'stock' => $this->faker->numberBetween(10, 100),
            'purchase_price' => $this->faker->randomFloat(2, 10, 50),
            'precio_venta' => $this->faker->randomFloat(2, 100, 500),
        ];
    }
}
