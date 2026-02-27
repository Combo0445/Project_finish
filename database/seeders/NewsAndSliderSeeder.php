<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\NewsImage;
use App\Models\Slider;

class NewsAndSliderSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Fake Sliders
        $sliderImages = [
            'https://picsum.photos/800/400?random=1',
            'https://picsum.photos/800/400?random=2',
            'https://picsum.photos/800/400?random=3'
        ];

        foreach ($sliderImages as $index => $url) {
            $contents = file_get_contents($url);
            $filename = 'slider_images/fake_slider_' . $index . '.jpg';
            \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $contents);

            Slider::create([
                'image' => $filename
            ]);
        }

        // 2. Create Fake News
        for ($i = 1; $i <= 6; $i++) {
            $news = News::create([
                'title' => "ข่าวประชาสัมพันธ์กิจกรรมผู้สูงอายุ ครั้งที่ $i",
                'content' => "<p>นี่คือรายละเอียดของข่าวสารปลอมที่สร้างขึ้นเพื่อทดสอบระบบหน้าจอแอดมิน คุณสามารถเข้าไปแก้ไขหรือลบข่าวนี้ได้ในภายหลัง</p><p>รายละเอียดเพิ่มเติมของกิจกรรมที่ $i จัดขึ้นเพื่อส่งเสริมสุขภาพและคุณภาพชีวิตของผู้สูงอายุในชุมชน</p>"
            ]);

            // Add 1-2 images per news
            $numImages = rand(1, 2);
            for ($j = 1; $j <= $numImages; $j++) {
                $contents = file_get_contents('https://picsum.photos/600/400?random=' . ($i * 10 + $j));
                $filename = 'news_images/fake_news_' . $i . '_' . $j . '.jpg';
                \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $contents);

                NewsImage::create([
                    'news_id' => $news->id,
                    'image_path' => $filename
                ]);
            }
        }
    }
}

