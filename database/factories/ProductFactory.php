<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cod_product' => $this->faker->unique()->numerify('PRO####'),
            'category_id' => function () {
                return Category::factory()->create()->id;
            },
            'desc' => $this->faker->text,
            'color' => $this->faker->colorName,
            'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL']),
            'stock_min' => $this->faker->numberBetween(1, 50),
            'stock' => $this->faker->numberBetween(0, 100),
            'purchase_price' => $this->faker->randomFloat(2, 1, 1000),
            'sale_price' => $this->faker->randomFloat(2, 10, 2000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

