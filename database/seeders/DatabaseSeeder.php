<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Advertisement;
use App\Models\BlogNew;
use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use App\Models\ReturnPolicy;
use App\Models\Service;
use App\Models\Textbook;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminSeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(AboutSeeder::class);
        $this->call(TermsAndConditionSeeder::class);
        $this->call(CategorySeeder::class);
        // Textbook::factory(count: 100)->create();
        // BlogNew::factory(count: 100)->create();
        // GalleryPhoto::factory(100)->create();
        // GalleryVideo::factory(100)->create();
        // Advertisement::factory(100)->create();
        // Service::factory(100)->create();
    }
}
