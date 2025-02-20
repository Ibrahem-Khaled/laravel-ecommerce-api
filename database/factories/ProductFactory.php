<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        // إنشاء نسخة من Faker باستخدام اللغة العربية (مثلاً: العربية السعودية)
        $faker = \Faker\Factory::create('ar_SA');

        return [
            'sub_category_id' => SubCategory::inRandomOrder()->first()->id ?? SubCategory::factory(),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['active', 'inactive']),
            // 'images' => json_encode([$this->faker->imageUrl(400, 400, 'products')]),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'quantity' => $this->faker->numberBetween(1, 100),
            'views' => $this->faker->numberBetween(0, 1000),
            'type' => $this->faker->randomElement(['basic', 'hot', 'new', 'special']),
        ];
    }
}
