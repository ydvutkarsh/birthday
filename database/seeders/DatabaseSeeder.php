<?php

namespace Database\Seeders;

use App\Models\BirthdayMessage;
use App\Models\BirthdayProfile;
use App\Models\GalleryImage;
use App\Models\LoveLetter;
use App\Models\Memory;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::updateOrCreate(['email' => env('ADMIN_EMAIL', 'admin@example.com')], [
            'name' => 'Birthday Admin', 'username' => env('ADMIN_USERNAME', 'admin'), 'role' => 'admin', 'status' => 'active',
            'password' => Hash::make(env('ADMIN_PASSWORD', 'ChangeMe!2026')),
        ]);
        User::firstOrCreate(['role' => 'guest'], [
            'name' => 'My Love', 'username' => env('BIRTHDAY_USERNAME', 'mylove'), 'role' => 'guest', 'status' => 'active',
            'password' => Hash::make(env('BIRTHDAY_PASSWORD', 'Surprise@2026')),
        ]);

        BirthdayProfile::updateOrCreate(['id' => 1], [
            'name' => 'My Love', 'nickname' => 'my favourite person', 'hero_title' => 'Happy Birthday, My Love ♥️',
            'hero_subtitle' => 'Today is all about you.', 'intro_message' => "Today isn't just your birthday…\nit's the day my favourite person came into this world.",
            'profile_image' => 'birthday/profile/profile-demo.png', 'cover_image' => 'birthday/profile/cover-demo.png',
        ]);
        SiteSetting::updateOrCreate(['id' => 1], [
            'site_title' => 'Happy Birthday', 'footer_message' => 'Made with every little piece of my heart.',
            'final_message' => 'If I could give you one thing, it would be the ability to see yourself through my eyes. Then you would know just how loved you are.',
            'final_photo' => 'birthday/gallery/picnic-demo.png',
        ]);
        LoveLetter::updateOrCreate(['id' => 1], [
            'title' => 'A little something I wanted to tell you…',
            'content' => 'You make ordinary days feel like memories worth keeping. Thank you for being exactly who you are. I love you, always.',
            'signature' => 'Yours, always ♥️',
        ]);
        foreach ([
            ['title' => 'Your Smile', 'icon' => '☼', 'message' => 'It changes the entire atmosphere of a room, and somehow it always finds me.'],
            ['title' => 'Your Kindness', 'icon' => '✦', 'message' => 'The way you care for people tells me everything beautiful about your heart.'],
            ['title' => 'The Way You Care', 'icon' => '♡', 'message' => 'You make love feel safe, warm, and wonderfully real.'],
        ] as $index => $message) {
            BirthdayMessage::updateOrCreate(['title' => $message['title']], $message + ['sort_order' => $index, 'status' => 'published']);
        }

        $demoMemories = [
            ['title' => 'The first little adventure', 'memory_date' => '2023-05-14', 'short_description' => 'Somewhere between the sunset and the lake, everything felt easy.', 'full_description' => 'I still remember how the whole evening seemed to slow down for us. The kind of day that quietly becomes a favourite.', 'cover_image' => 'birthday/memories/lakeside-demo.png', 'sort_order' => 1, 'is_featured' => true, 'status' => 'published'],
            ['title' => 'Coffee, conversations & you', 'memory_date' => '2023-08-22', 'short_description' => 'A small table, two cups, and a conversation I never wanted to end.', 'full_description' => 'It was just coffee, but with you even the smallest plans become stories worth keeping.', 'cover_image' => 'birthday/memories/cafe-demo.png', 'sort_order' => 2, 'is_featured' => true, 'status' => 'published'],
            ['title' => 'Dancing in the rain', 'memory_date' => '2024-07-06', 'short_description' => 'The weather changed. We stayed exactly where we were.', 'full_description' => 'We did not need perfect weather for a perfect memory. We only needed each other and a little courage to get wonderfully drenched.', 'cover_image' => 'birthday/memories/rain-demo.png', 'sort_order' => 3, 'is_featured' => true, 'status' => 'published'],
        ];
        foreach ($demoMemories as $memoryData) {
            $memory = Memory::updateOrCreate(['title' => $memoryData['title']], $memoryData);
            $memory->images()->firstOrCreate(['image' => $memoryData['cover_image']], ['caption' => 'A demo memory frame', 'sort_order' => 0]);
        }

        foreach ([
            ['title' => 'A quiet evening', 'image' => 'birthday/profile/cover-demo.png', 'caption' => 'The city looked softer that evening.', 'memory_date' => '2024-02-18', 'sort_order' => 1, 'is_featured' => true],
            ['title' => 'Our little cafe ritual', 'image' => 'birthday/memories/cafe-demo.png', 'caption' => 'Two cups and nowhere else to be.', 'memory_date' => '2023-08-22', 'sort_order' => 2, 'is_featured' => true],
            ['title' => 'After the rain', 'image' => 'birthday/memories/rain-demo.png', 'caption' => 'Some memories are better a little blurry.', 'memory_date' => '2024-07-06', 'sort_order' => 3, 'is_featured' => false],
            ['title' => 'A day by the lake', 'image' => 'birthday/memories/lakeside-demo.png', 'caption' => 'The first of many little adventures.', 'memory_date' => '2023-05-14', 'sort_order' => 4, 'is_featured' => true],
            ['title' => 'One last celebration', 'image' => 'birthday/gallery/picnic-demo.png', 'caption' => 'For all the birthdays still to come.', 'memory_date' => '2025-09-01', 'sort_order' => 5, 'is_featured' => true],
        ] as $photo) {
            GalleryImage::updateOrCreate(['title' => $photo['title']], $photo);
        }
    }
}
