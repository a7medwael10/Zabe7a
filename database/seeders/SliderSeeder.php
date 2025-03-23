<?php

namespace Database\Seeders;

use App\Models\Slider; // تأكد من وجود الموديل
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Slider::insert([
            [
                'offer_id'    => 1, // تأكد إن فيه offer عنده id = 1 أو خليه null
                'image_path'  => 'slider/slider.png',
                'title'       => 'تخفيضات العيد',
                'description' => 'استمتع بخصومات تصل إلى 50% بمناسبة العيد.',
                'sort_order'  => 1,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'offer_id'    => null, // بدون عرض
                'image_path'  => 'slider/slider.png',
                'title'       => 'منتجات طازجة',
                'description' => 'أفضل اللحوم الطازجة مباشرة من المزرعة إلى بيتك.',
                'sort_order'  => 2,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'offer_id'    => 3,
                'image_path'  => 'slider/slider.png',
                'title'       => 'خدمة التقطيع المجانية',
                'description' => 'اطلب دلوقتي واستمتع بتقطيع مجاني حسب اختيارك.',
                'sort_order'  => 3,
                'is_active'   => false, // مخفية حاليًا
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
