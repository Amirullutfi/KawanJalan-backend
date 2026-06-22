<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JogjaDestinationSeeder extends Seeder
{
    public function run(): void
    {
        $alam = Category::where('slug', 'wisata-alam')->first();
        $bahari = Category::where('slug', 'wisata-bahari')->first();
        $budaya = Category::where('slug', 'wisata-budaya-sejarah')->first();
        $kuliner = Category::where('slug', 'wisata-kuliner')->first();

        // 1. Candi Borobudur
        Destination::create([
            'category_id' => $budaya->id ?? 3,
            'title' => 'Candi Borobudur',
            'slug' => Str::slug('Candi Borobudur'),
            'excerpt' => 'Candi Buddha terbesar di dunia peninggalan abad ke-9 dengan arsitektur megah.',
            'description' => 'Candi Borobudur adalah monumen Buddha terbesar di dunia yang dibangun pada masa Dinasti Syailendra. Dikenal dengan relief dinding yang rumit dan stupa-stupa berlubang yang berisi arca Buddha, Borobudur merupakan keajaiban arsitektur kuno dan destinasi wajib saat berkunjung ke wilayah Jogja dan sekitarnya.',
            'location' => 'Magelang, Jawa Tengah',
            'featured' => true,
            'main_image' => 'https://images.unsplash.com/photo-1596402184320-417e7178b2cb?w=800'
        ]);

        // 2. Candi Prambanan
        Destination::create([
            'category_id' => $budaya->id ?? 3,
            'title' => 'Candi Prambanan',
            'slug' => Str::slug('Candi Prambanan'),
            'excerpt' => 'Kompleks candi Hindu terbesar di Indonesia yang menjulang anggun.',
            'description' => 'Candi Prambanan adalah mahakarya kebudayaan Hindu dari abad ke-10 yang didedikasikan untuk Trimurti (Dewa Brahma, Wisnu, dan Siwa). Candi utamanya yang ramping dan menjulang setinggi 47 meter menjadi daya tarik visual yang luar biasa, terutama saat senja tiba.',
            'location' => 'Sleman, Yogyakarta',
            'featured' => true,
            'main_image' => 'https://images.unsplash.com/photo-1596401057633-54a8fe8ef647?w=800'
        ]);

        // 3. Goa Pindul
        Destination::create([
            'category_id' => $alam->id ?? 1,
            'title' => 'Goa Pindul Gunungkidul',
            'slug' => Str::slug('Goa Pindul Gunungkidul'),
            'excerpt' => 'Sensasi cave tubing menyusuri sungai bawah tanah di dalam gua karst eksotis.',
            'description' => 'Goa Pindul menawarkan pengalaman unik berupa "Cave Tubing", di mana wisatawan akan menyusuri aliran sungai bawah tanah di dalam gua menggunakan ban pelampung. Sambil mengapung santai, Anda dapat menikmati keindahan ornamen stalaktit dan stalagmit yang terbentuk secara alami ribuan tahun.',
            'location' => 'Gunungkidul, Yogyakarta',
            'featured' => false,
            'main_image' => 'https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=800'
        ]);

        // 4. Heha Sky View
        Destination::create([
            'category_id' => $alam->id ?? 1,
            'title' => 'Heha Sky View',
            'slug' => Str::slug('Heha Sky View'),
            'excerpt' => 'Spot panorama terbaik melihat lanskap kota Yogyakarta dari ketinggian.',
            'description' => 'Heha Sky View adalah destinasi wisata modern kekinian di perbukitan Gunungkidul. Tempat ini sangat populer berkat spot-spot foto instagenik berlatar belakang hamparan kota Yogyakarta dari ketinggian. Waktu terbaik berkunjung adalah sore hari untuk menikmati pemandangan matahari terbenam dan gemerlap lampu kota di malam hari.',
            'location' => 'Gunungkidul, Yogyakarta',
            'featured' => true,
            'main_image' => 'https://images.unsplash.com/photo-1629853903100-8d5ec42c0c0f?w=800'
        ]);

        // 5. Pantai Timang
        Destination::create([
            'category_id' => $bahari->id ?? 2,
            'title' => 'Pantai Timang',
            'slug' => Str::slug('Pantai Timang'),
            'excerpt' => 'Uji adrenalin dengan menaiki gondola kayu tradisional melintasi ombak besar Samudra Hindia.',
            'description' => 'Pantai Timang bukan sekadar pantai biasa. Daya tarik utamanya adalah sebuah pulau karang (Pulau Timang) yang dihubungkan ke tebing daratan oleh kereta gantung kayu tradisional (gondola) dan jembatan gantung yang mendebarkan. Pantai ini sangat cocok untuk Anda yang menyukai tantangan dan adrenalin.',
            'location' => 'Gunungkidul, Yogyakarta',
            'featured' => false,
            'main_image' => 'https://images.unsplash.com/photo-1582298642055-6b5894b9ff52?w=800'
        ]);

        // 6. Jalan Malioboro
        Destination::create([
            'category_id' => $kuliner->id ?? 4,
            'title' => 'Jalan Malioboro',
            'slug' => Str::slug('Jalan Malioboro'),
            'excerpt' => 'Jantung kota Jogja, surga belanja suvenir dan kuliner angkringan malam.',
            'description' => 'Jalan Malioboro adalah ikon tak terpisahkan dari Yogyakarta. Kawasan pedestrian ini selalu ramai oleh wisatawan yang berburu batik, kerajinan tangan, dan suvenir khas Jogja. Di malam hari, Malioboro bertransformasi menjadi pusat kuliner angkringan dan musisi jalanan yang menghidupkan suasana magis kota pelajar ini.',
            'location' => 'Kota Yogyakarta',
            'featured' => true,
            'main_image' => 'https://images.unsplash.com/photo-1610013575918-a6dff5aeb665?w=800'
        ]);
        
        // 7. Obelix Hills
        Destination::create([
            'category_id' => $alam->id ?? 1,
            'title' => 'Obelix Hills',
            'slug' => Str::slug('Obelix Hills'),
            'excerpt' => 'Wisata bukit batu purba kekinian dengan pemandangan sunset memukau.',
            'description' => 'Berada di atas bebatuan purba yang luas, Obelix Hills menawarkan tempat bersantai yang luar biasa estetik dengan pemandangan lembah hijau dan matahari terbenam yang sempurna. Tempat ini dilengkapi puluhan spot foto keren, bean bag untuk bersantai, dan live music yang menghangatkan suasana.',
            'location' => 'Sleman, Yogyakarta',
            'featured' => false,
            'main_image' => 'https://images.unsplash.com/photo-1596402447953-270f2095cc60?w=800'
        ]);
    }
}
