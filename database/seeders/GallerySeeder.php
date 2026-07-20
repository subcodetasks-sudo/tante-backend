<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'أجواء المطعم',
                'type' => 'image',
                'image' => null,
                'video_url' => null,
                'sort_order' => 1,
            ],
            [
                'title' => 'أشهى المشاوي',
                'type' => 'image',
                'image' => null,
                'video_url' => null,
                'sort_order' => 2,
            ],
            [
                'title' => 'جولة داخل المطعم',
                'type' => 'video',
                'image' => null,
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'sort_order' => 3,
            ],
            [
                'title' => 'إعداد الأطباق',
                'type' => 'video',
                'image' => null,
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'sort_order' => 4,
            ],
        ];

        foreach ($items as $item) {
            Gallery::query()->updateOrCreate(
                [
                    'title' => $item['title'],
                    'type' => $item['type'],
                ],
                $item,
            );
        }
    }
}
