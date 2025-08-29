<?php

namespace Database\Factories;

use App\Models\GalleryVideo;
use App\Models\Language;
use App\Models\GalleryVideoMedia;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GalleryVideoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GalleryVideo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $imageUrl = "https://picsum.photos/800/600";
        $imageContents = Http::get($imageUrl)->body();
        $filename = 'gallery_video_' . uniqid() . '.jpg';
        Storage::disk('public')->put('uploads/gallery-videos/' . $filename, $imageContents);

        return [
            'image' => 'uploads/gallery-videos/' . $filename,
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (GalleryVideo $galleryVideo) {
            // Create translations for the gallery video
            $languages = Language::all();
            foreach ($languages as $language) {
                $title = $this->faker->words(3, true);
                $slug = Str::slug($title);


                $keywords = $this->faker->words(5);
                $seoKeywords = array_map(function($word) {
                    return ['value' => $word];
                }, $keywords);

                // Create translations in a single record
                $galleryVideo->translations()->create([
                    'locale' => $language->code,
                    'title' => $title,
                    'slug' => $slug,
                    'seo_keywords'=> json_encode($seoKeywords),
                    'seo_description'=> $this->faker->sentence(10),
                ]);
            }

            // Create 2-4 media files (videos) for each gallery video
            $mediaCount = rand(2, 4);
            for ($i = 0; $i < $mediaCount; $i++) {
                // Create placeholder video file content (you can't really download video from picsum)
                $videoContent = 'RIFF-AVI_PLACEHOLDER_VIDEO_FILE' . uniqid(); // Placeholder content
                $mediaFilename = 'media_' . uniqid() . '.mp4';
                Storage::disk('public')->put('uploads/gallery-videos/media/' . $mediaFilename, $videoContent);

                GalleryVideoMedia::create([
                    'file' => 'uploads/gallery-videos/media/' . $mediaFilename,
                    'status' => 1,
                    'order' => $i,
                    'gallery_video_id' => $galleryVideo->id
                ]);
            }
        });
    }
}