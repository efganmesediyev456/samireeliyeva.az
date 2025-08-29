<?php

namespace Database\Factories;

use App\Models\BlogNew;
use App\Models\Language;
use App\Models\BlogNewMedia;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class BlogNewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BlogNew::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $imageUrl = "https://picsum.photos/800/600";
        $imageContents = Http::get($imageUrl)->body();
        $filename = 'blognews_' . uniqid() . '.jpg';
        Storage::disk('public')->put('uploads/blognews/' . $filename, $imageContents);

        return [
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'image' => 'uploads/blognews/' . $filename,
            'view' => $this->faker->numberBetween(0, 10000), // Random view count
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (BlogNew $blogNew) {
            // Create translations for the blog news
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
                $blogNew->translations()->create([
                    'locale' => $language->code,
                    'title' => $title,
                    'slug' => $slug,
                    'subtitle' => $subtitle,
                    'description'=> $this->faker->paragraphs(3, true),
                    'seo_keywords'=> json_encode($seoKeywords),
                    'seo_description'=> $this->faker->sentence(10),
                ]);
            }

            // Create 3-5 media files for each blog
            $mediaCount = rand(3, 5);
            for ($i = 0; $i < $mediaCount; $i++) {
                $mediaUrl = "https://picsum.photos/800/400";
                $mediaContents = Http::get($mediaUrl)->body();
                $mediaFilename = 'media_' . uniqid() . '.jpg';
                Storage::disk('public')->put('uploads/blognews/media/' . $mediaFilename, $mediaContents);

                BlogNewMedia::create([
                    'file' => 'uploads/blognews/media/' . $mediaFilename,
                    'status' => 1,
                    'order' => $i,
                    'blog_new_id' => $blogNew->id
                ]);
            }
        });
    }
}