<?php

namespace Database\Factories;

use App\Models\Textbook;
use App\Models\Language;
use App\Models\TextbookMedia;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class TextbookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Textbook::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $imageUrl = "https://picsum.photos/800/600";
        $imageContents = Http::get($imageUrl)->body();
        $filename = 'textbook_' . uniqid() . '.jpg';
        Storage::disk('public')->put('uploads/textbooks/' . $filename, $imageContents);

        return [
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'image' => 'uploads/textbooks/' . $filename,
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Textbook $textbook) {
            // Create translations for the textbook
            $languages = Language::all();
            foreach ($languages as $language) {
                $title = $this->faker->words(3, true);
                $subtitle = $this->faker->words(3, true);
                $slug = Str::slug($title);


                $keywords = $this->faker->words(5);
                $seoKeywords = array_map(function($word) {
                    return ['value' => $word];
                }, $keywords);

                // Create title translation
                $textbook->translations()->create([
                    'locale' => $language->code,
                    'title' => $title,
                    'slug' => $slug,
                    'subtitle' => $subtitle,
                    'description'=> $this->faker->paragraphs(3, true),
                    'seo_keywords'=> json_encode($seoKeywords),
                    'seo_description'=> $this->faker->sentence(10),
                ]);
            }

            // Create 3-5 media files for each textbook
            $mediaCount = rand(2, 5);
            for ($i = 0; $i < $mediaCount; $i++) {
                $mediaUrl = "https://picsum.photos/800/400";
                $mediaContents = Http::get($mediaUrl)->body();
                $mediaFilename = 'media_' . uniqid() . '.jpg';
                Storage::disk('public')->put('uploads/textbooks/media/' . $mediaFilename, $mediaContents);

                TextbookMedia::create([
                    'file' => 'uploads/textbooks/media/' . $mediaFilename,
                    'status' => 1,
                    'order' => $i,
                    'textbook_id' => $textbook->id
                ]);
            }
        });
    }
}