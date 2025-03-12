<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = Section::all();

        foreach ($sections as $section) {
            switch ($section->slug) {

                case 'zbaih':
                    $categories = [
                        ['name' => 'خروف بلدي', 'slug' => 'kharouf-baladi'],
                        ['name' => 'ماعز', 'slug' => 'maez'],
                        ['name' => 'جدي رضيع', 'slug' => 'jadi-radea'],
                    ];
                    break;

                case 'lhoom':
                    $categories = [
                        ['name' => 'لحم مفروم', 'slug' => 'lahm-mafroom'],
                        ['name' => 'ريش ضاني', 'slug' => 'reyash-dani'],
                        ['name' => 'كبدة بلدي', 'slug' => 'kebda-balady'],
                    ];
                    break;

                case 'mshwyat':
                    $categories = [
                        ['name' => 'كفتة مشوية', 'slug' => 'kofta-mashwy'],
                        ['name' => 'شيش طاووق', 'slug' => 'shish-tawook'],
                        ['name' => 'ريش مشوية', 'slug' => 'reyash-mashwy'],
                    ];
                    break;

                case 'aldwajn':
                    $categories = [
                        ['name' => 'دجاجة كاملة', 'slug' => 'dajaja-kamla'],
                        ['name' => 'صدور دجاج', 'slug' => 'sodor-dajaj'],
                        ['name' => 'أوراك دجاج', 'slug' => 'awrak-dajaj'],
                    ];
                    break;

                default:
                    $categories = [];
            }

            foreach ($categories as $category) {
                Category::updateOrCreate(
                    ['slug' => $category['slug']],
                    [
                        'section_id' => $section->id,  // الربط مع القسم
                        'name' => $category['name'],
                        'slug' => $category['slug'],
                        'logo' => 'logos/default.png',
                        'description' => $category['name'] . ' من أفضل المنتجات.',
                        'sort_order' => 0,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
