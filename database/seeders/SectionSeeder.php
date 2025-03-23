<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'name' => 'ذبائح',
                'slug' => 'zbaih',
                'icon' => 'sections-icons/section.png',
                'description' => 'قسم الذبائح الطازجة',
            ],
            [
                'name' => 'لحوم',
                'slug' => 'lhoom',
                'icon' => 'sections-icons/section.png',
                'description' => 'قسم اللحوم المتنوعة',
            ],
            [
                'name' => 'مشويات',
                'slug' => 'mshwyat',
                'icon' => 'sections-icons/section.png',
                'description' => 'قسم المشويات الطازجة',
            ],
            [
                'name' => 'الدواجن',
                'slug' => 'aldwajn',
                'icon' => 'sections-icons/section.png',
            ],
        ];

        foreach ($sections as $section) {
            Section::create([
                'name' => $section['name'],
                'slug' => $section['slug'],
                'icon' => $section['icon'],
            ]);
        }

        $this->command->info('✅ تم إنشاء الأقسام بنجاح');
    }
}
