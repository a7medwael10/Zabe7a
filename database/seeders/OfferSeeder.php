<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Offer;
use Illuminate\Support\Str;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offers = [
            [
                'category_id' => 5, // تأكد أن لديك category_id=1 موجود!
                'title' => 'عرض ذبيحة بلدي فاخر',
                'sub_title' => 'أجود أنواع الذبائح الطازجة',
                'slug' => Str::slug('عرض ذبيحة بلدي فاخر'),
                'thumbnail_path' => 'ads-thumbnails/ad.png',
                'description' => 'ذبيحة بلدي طازجة مذبوحة تحت إشراف طبي بيطري كامل. الوزن الصافي 40 كيلو.',
                'original_price' => 2500.00,
                'discount_percentage' => 10.00,
                'offer_price' => 2250.00,
                'gift' => 'كبدة مجانية 1 كجم',
                'rating' => 4.8,
                'quantity_sold' => 20,
                'quantity_available' => 50,
                'views_count' => 120,
                'reviews_count' => 15,
                'starts_at' => now()->subDays(2),
                'expires_at' => now()->addDays(5),
                'is_active' => true,
            ],
            [
                'category_id' => 4,
                'title' => 'عرض نصف ذبيحة نعيمي',
                'sub_title' => 'مناسب للعائلات الصغيرة',
                'slug' => Str::slug('عرض نصف ذبيحة نعيمي'),
                'thumbnail_path' => 'ads-thumbnails/ad.png',
                'description' => 'نصف ذبيحة نعيمي بعناية فائقة، تزن حوالي 20 كيلو. مذبوحة حسب الشريعة الإسلامية.',
                'original_price' => 1300.00,
                'discount_percentage' => 5.00,
                'offer_price' => 1235.00,
                'gift' => null,
                'rating' => 4.5,
                'quantity_sold' => 35,
                'quantity_available' => 40,
                'views_count' => 200,
                'reviews_count' => 25,
                'starts_at' => now()->subDays(1),
                'expires_at' => now()->addDays(3),
                'is_active' => true,
            ],
            [
                'category_id' => 2,
                'title' => 'عرض تيس بلدي صغير',
                'sub_title' => 'مذبوح طازج ومجهز للتوصيل',
                'slug' => Str::slug('عرض تيس بلدي صغير'),
                'thumbnail_path' => 'ads-thumbnails/ad.png',
                'description' => 'تيس بلدي صغير، الوزن يتراوح بين 18-20 كيلو، مذبوح طازج ومغلف بطريقة صحية.',
                'original_price' => 1800.00,
                'discount_percentage' => 8.00,
                'offer_price' => 1656.00,
                'gift' => 'شحم مجاني 2 كجم',
                'rating' => 4.7,
                'quantity_sold' => 10,
                'quantity_available' => 30,
                'views_count' => 90,
                'reviews_count' => 12,
                'starts_at' => now(),
                'expires_at' => now()->addDays(7),
                'is_active' => true,
            ],
        ];

        foreach ($offers as $offer) {
            Offer::create($offer);
        }
    }
}
