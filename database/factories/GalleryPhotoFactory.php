<?php

namespace Database\Factories;

use App\Models\GalleryPhoto;
use App\Models\Language;
use App\Models\GalleryPhotoMedia;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GalleryPhotoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GalleryPhoto::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $imageUrl = "https://picsum.photos/800/600";
        $imageContents = Http::get($imageUrl)->body();
        $filename = 'gallery_photo_' . uniqid() . '.jpg';
        Storage::disk('public')->put('uploads/gallery-photos/' . $filename, $imageContents);

        return [
            'image' => 'uploads/gallery-photos/' . $filename,
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (GalleryPhoto $galleryPhoto) {
            // Create translations for the gallery photo
            $languages = Language::all();
            foreach ($languages as $language) {
                $title = $this->faker->words(3, true);
                $slug = Str::slug($title);

                $keywords = $this->faker->words(5);
                $seoKeywords = array_map(function($word) {
                    return ['value' => $word];
                }, $keywords);

                // Create translations in a single record
                $galleryPhoto->translations()->create([
                    'locale' => $language->code,
                    'title' => $title,
                    'slug' => $slug,
                    'seo_keywords'=> json_encode($seoKeywords),
                    'seo_description'=> $this->faker->sentence(10),
                ]);
            }

            // Create 3-5 media files for each gallery photo
            $mediaCount = rand(3, 5);
            for ($i = 0; $i < $mediaCount; $i++) {
                $mediaUrl = "https://picsum.photos/800/600";
                $mediaContents = Http::get($mediaUrl)->body();
                $mediaFilename = 'media_' . uniqid() . '.jpg';
                Storage::disk('public')->put('uploads/gallery-photos/media/' . $mediaFilename, $mediaContents);

                GalleryPhotoMedia::create([
                    'file' => 'uploads/gallery-photos/media/' . $mediaFilename,
                    'status' => 1,
                    'order' => $i,
                    'gallery_photo_id' => $galleryPhoto->id
                ]);
            }
        });
    }
}