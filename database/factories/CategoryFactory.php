<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word, // اسم عشوائي
            'description' => $this->faker->sentence, // وصف عشوائي
            'status' => $this->faker->randomElement(['active', 'inactive']), // حالة عشوائية
            'image' => $this->faker->imageUrl(300, 300, 'cats', true, 'Category'), // رابط صورة عشوائية
        ];
    }
}
