<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $this->command->warn('❗ مفيش مستخدمين مضافين. لازم تضيف يوزر.');
            return;
        }

        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->warn('❗ مفيش فئات مضافة. لازم تضيف كاتيجوري الأول.');
            return;
        }

        foreach ($categories as $category) {

            // نصنع عنوان لكل إعلان بناءً على اسم الكاتيجوري
            $adTitle = 'أفضل ' . $category->name;
            $slug = Str::slug($adTitle) . '-' . $category->id;

            Ad::create([
                'category_id' => $category->id,
                'title' => $adTitle,
                'sub_title' => $adTitle,
                'slug' => $slug,
                'thumbnail_path' =>'ads-thumbnails/ad.png',
                'description' => 'إعلان خاص بـ ' . $category->name . '، جودة عالية وأسعار مناسبة.',
                'price' => rand(100, 5000), // حسب نوع القسم
                'quantity_available' => rand(1, 20),
                'quantity_sold' => rand(0, 10),
                'weight' => rand(1, 5),
                'rating' => rand(40, 50) / 10, // 4.0 -> 5.0
                'views_count' => rand(10, 500),
                'reviews_count' => rand(0, 50),
                'status' => 'active',
                'approved_at' => now(),
                'expires_at' => now()->addDays(30),
            ]);
        }

        $this->command->info('✅ تم إنشاء إعلان لكل فئة بنجاح');
    }
}
