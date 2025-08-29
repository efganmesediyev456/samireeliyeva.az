<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Create main service image
        $imageUrl = "https://picsum.photos/800/600";
        $imageContents = Http::get($imageUrl)->body();
        $filename = 'service_' . uniqid() . '.jpg';
        Storage::disk('public')->put('uploads/services/' . $filename, $imageContents);

        // Create icon image (smaller size)
        $iconUrl = "https://picsum.photos/100/100";
        $iconContents = Http::get($iconUrl)->body();
        $iconFilename = 'service_icon_' . uniqid() . '.jpg';
        Storage::disk('public')->put('uploads/services/' . $iconFilename, $iconContents);

        return [
            'image' => 'uploads/services/' . $filename,
            'icon' => 'uploads/services/' . $iconFilename,
            'status' => $this->faker->randomElement([0, 1]),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Service $service) {
            // Create translations for the service
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
                $service->translations()->create([
                    'locale' => $language->code,
                    'title' => $title,
                    'slug' => $slug,
                    'subtitle' => $subtitle,
                    'description'=> $this->faker->paragraphs(3, true),
                    'seo_keywords'=> json_encode($seoKeywords),
                    'seo_description'=> $this->faker->sentence(10),
                ]);
            }
        });
    }
}