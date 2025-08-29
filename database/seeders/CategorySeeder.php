<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

    
        foreach(Category::get() as $cat){
            $cat->delete();
        }

        $datas = [
            [
                'az' => 'Ümumi təhsil üzrə işə qəbul',
                'en' => 'General education recruitment',
                'ru' => 'Прием на работу в сферу общего образования',
            ],
            [
                'az' => 'Məktəbəqədər təhsil üzrə  işə qəbul',
                'en' => 'Preschool education recruitment',
                'ru' => 'Прием на работу в дошкольное образование',
            ],
            [
                'az' => 'Peşə təhsili üzrə işə qəbul',
                'en' => 'Vocational education recruitment',
                'ru' => 'Прием на работу в профессиональное образование',
            ],
            [
                'az' => 'Musiqi məktəbi üzrə işə qəbul',
                'en' => 'Music school recruitment',
                'ru' => 'Прием на работу в музыкальную школу',
            ],
            [
                'az' => 'Kurikulum',
                'en' => 'Curriculum',
                'ru' => 'Куррикулум',
            ],
        ];

        foreach ($datas as $data) {
            $category = Category::create(); 

            foreach ($data as $locale => $text) {
                $category->translations()->create([
                    'locale' => $locale,
                    'title' => $text, 
                    'slug'=> Str::slug($text)
                ]);
            }
        }

    }
}
