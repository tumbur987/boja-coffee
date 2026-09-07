<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name'         => 'SiBoja Coffee',
            'site_tagline'      => 'Nikmati Setiap Tegukan Kopi Terbaik',
            'site_description'  => 'SiBoja Coffee menyajikan kopi premium dengan biji pilihan dari petani terbaik Indonesia. Pesan langsung dari meja Anda, scan QR, pilih menu, dan bayar dengan mudah.',
            'site_email'        => 'hello@siboja.com',
            'site_phone'        => '081234567890',
            'site_whatsapp'     => '6281234567890',
            'site_address'      => 'Jl. Kopi No. 123, Kota Bandung, Jawa Barat',
            'site_instagram'    => '@sibojacoffee',
            'site_tiktok'       => '@sibojacoffee',
            'site_facebook'     => 'SiBoja Coffee',
            'hero_title'        => 'Nikmati Setiap Tegukan <em>Kopi Terbaik</em>',
            'hero_subtitle'     => 'SiBoja Coffee menyajikan kopi premium dengan biji pilihan. Pesan langsung dari meja Anda, scan QR, pilih menu, dan bayar dengan mudah.',
            'hero_badge'        => 'Coffee Shop Terbaik di Kota',
            'about_title'       => 'Kopi yang Dibuat dengan Cinta',
            'about_description' => 'Setiap cangkir yang kami sajikan adalah hasil dari dedikasi dan passion terhadap kopi berkualitas.',
            'cta_title'         => 'Siap Menikmati Kopi Terbaik?',
            'cta_description'   => 'Temukan SiBoja Coffee terdekat atau pesan langsung dari meja Anda melalui scan QR code.',
            'footer_text'       => 'All rights reserved.',
            'meta_title'        => 'SiBoja Coffee - Nikmati Setiap Tegukan',
            'meta_description'  => 'Coffee shop premium dengan kopi pilihan. Pesan langsung dari meja via QR code.',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
