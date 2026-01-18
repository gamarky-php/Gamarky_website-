<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MediaLibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if table already has data
        if (DB::table('media_library')->count() > 0) {
            $this->command->info('Media library already has data. Skipping...');
            return;
        }

        $userId = DB::table('users')->first()?->id ?? 1;
        $now = Carbon::now();

        $dummyMedia = [
            [
                'user_id' => $userId,
                'disk' => 'public',
                'path' => 'media-library/demo-image-1.jpg',
                'filename' => 'demo-image-1.jpg',
                'original_name' => 'صورة تجريبية 1.jpg',
                'mime_type' => 'image/jpeg',
                'extension' => 'jpg',
                'size_bytes' => 245760,
                'width' => 1920,
                'height' => 1080,
                'alt' => 'صورة تجريبية للمنتج',
                'caption' => 'هذه صورة تجريبية للعرض فقط',
                'is_public' => true,
                'created_at' => $now->copy()->subDays(10),
                'updated_at' => $now->copy()->subDays(10),
            ],
            [
                'user_id' => $userId,
                'disk' => 'public',
                'path' => 'media-library/demo-image-2.png',
                'filename' => 'demo-image-2.png',
                'original_name' => 'شعار الشركة.png',
                'mime_type' => 'image/png',
                'extension' => 'png',
                'size_bytes' => 89120,
                'width' => 800,
                'height' => 600,
                'alt' => 'شعار الشركة',
                'is_public' => true,
                'created_at' => $now->copy()->subDays(8),
                'updated_at' => $now->copy()->subDays(8),
            ],
            [
                'user_id' => $userId,
                'disk' => 'public',
                'path' => 'media-library/document-sample.pdf',
                'filename' => 'document-sample.pdf',
                'original_name' => 'وثيقة تجريبية.pdf',
                'mime_type' => 'application/pdf',
                'extension' => 'pdf',
                'size_bytes' => 512000,
                'caption' => 'مستند PDF تجريبي',
                'is_public' => true,
                'created_at' => $now->copy()->subDays(5),
                'updated_at' => $now->copy()->subDays(5),
            ],
            [
                'user_id' => $userId,
                'disk' => 'public',
                'path' => 'media-library/video-demo.mp4',
                'filename' => 'video-demo.mp4',
                'original_name' => 'فيديو تجريبي.mp4',
                'mime_type' => 'video/mp4',
                'extension' => 'mp4',
                'size_bytes' => 5242880,
                'width' => 1280,
                'height' => 720,
                'caption' => 'فيديو توضيحي',
                'is_public' => true,
                'created_at' => $now->copy()->subDays(3),
                'updated_at' => $now->copy()->subDays(3),
            ],
            [
                'user_id' => $userId,
                'disk' => 'public',
                'path' => 'media-library/presentation.pptx',
                'filename' => 'presentation.pptx',
                'original_name' => 'عرض تقديمي.pptx',
                'mime_type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'extension' => 'pptx',
                'size_bytes' => 1048576,
                'is_public' => false,
                'created_at' => $now->copy()->subDays(1),
                'updated_at' => $now->copy()->subDays(1),
            ],
        ];

        DB::table('media_library')->insert($dummyMedia);

        $this->command->info('Media library seeded with ' . count($dummyMedia) . ' dummy files.');
        $this->command->warn('Note: These are dummy records. Actual files do not exist in storage.');
    }
}
