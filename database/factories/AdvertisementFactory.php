<?php

namespace Database\Factories;

use App\Models\Advertisement;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class AdvertisementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Advertisement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $imageUrl = "https://picsum.photos/800/600"; // Banner size for advertisements
        $imageContents = Http::get($imageUrl)->body();
        $filename = 'advertisement_' . uniqid() . '.jpg';
        Storage::disk('public')->put('uploads/advertisements/' . $filename, $imageContents);

        return [
            'image' => 'uploads/advertisements/' . $filename,
            'url' => $this->faker->url(),
            'status' => $this->faker->randomElement([0, 1]),
            'order' => $this->faker->numberBetween(0, 100),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Advertisement $advertisement) {
            // Create translations for the advertisement
            $languages = Language::all();
            foreach ($languages as $language) {
                $title = $this->faker->words(3, true);
                $subtitle = $this->faker->words(3, true);
                $slug = Str::slug($title);


                $keywords = $this->faker->words(5);
                $seoKeywords = array_map(function($word) {
                    return ['value' => $word];
                }, $keywords);

                // Create translations in a single record
                $advertisement->translations()->create([
                    'locale' => $language->code,
                    'title' => $title,
                    'slug' => $slug,
                    'subtitle' => $subtitle,
                    'description'=> $this->faker->paragraphs(2, true),
                    'seo_keywords'=> json_encode($seoKeywords),
                    'seo_description'=> $this->faker->sentence(10),
                ]);
            }
        });
    }
}