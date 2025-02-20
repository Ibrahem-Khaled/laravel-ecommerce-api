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
            // اختيار فئة فرعية عشوائية، وإذا لم توجد فئة سيتم إنشاء واحدة باستخدام المصنع
            'sub_category_id' => SubCategory::inRandomOrder()->first()->id ?? SubCategory::factory(),
            // اختيار مستخدم عشوائي، وإذا لم يوجد مستخدم سيتم إنشاء واحد باستخدام المصنع
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            // اسم المنتج (كلمة عربية عشوائية)
            'name' => $faker->word,
            // وصف مختصر للمنتج باللغة العربية
            'description' => $faker->sentence,
            // حالة المنتج: نشط أو غير نشط
            'status' => $faker->randomElement(['active', 'inactive']),
            // سعر المنتج بدقة رقمية تصل إلى رقمين عشريين
            'price' => $faker->randomFloat(2, 10, 1000),
            // كمية المنتج المتوفرة
            'quantity' => $faker->numberBetween(1, 100),
            // عدد مشاهدات المنتج
            'views' => $faker->numberBetween(0, 1000),
            // نوع المنتج: عادي، ساخن، جديد أو خاص
            'type' => $faker->randomElement(['basic', 'hot', 'new', 'special']),
        ];
    }
}
