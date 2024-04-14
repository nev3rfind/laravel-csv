<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sku' => $this->faker->unique()->bothify('SKU###'),
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'brand' => $this->faker->company
        ];
    }
}
