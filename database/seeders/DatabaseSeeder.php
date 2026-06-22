<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\TourPackage;
use App\Models\Destination;
use App\Models\Event;
use App\Models\Testimonial;
use App\Models\Article;
use App\Models\ArticleImage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin User
        User::create([
            'name' => 'Administrator KawanJalan Tour & Travel',
            'email' => 'admin@kawanjalan.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Seed Categories
        $alam = Category::create([
            'name' => 'Wisata Alam',
            'slug' => 'wisata-alam',
            'description' => 'Destinasi wisata keindahan alam pegunungan, hutan, dan kawah.',
        ]);

        $bahari = Category::create([
            'name' => 'Wisata Bahari',
            'slug' => 'wisata-bahari',
            'description' => 'Keindahan pantai pasir putih, terumbu karang, dan bawah laut.',
        ]);

        $budaya = Category::create([
            'name' => 'Wisata Budaya & Sejarah',
            'slug' => 'wisata-budaya-sejarah',
            'description' => 'Warisan budaya leluhur, candi bersejarah, dan adat istiadat.',
        ]);

        $kuliner = Category::create([
            'name' => 'Wisata Kuliner',
            'slug' => 'wisata-kuliner',
            'description' => 'Petualangan rasa dengan mencicipi masakan khas nusantara.',
        ]);


        // 3a. Seed Merapi Jeep Packages
        $getMerapiDescription = function ($packageName, $destinations, $duration) {
            $destList = '';
            foreach ($destinations as $dest) {
                $destList .= "<li style=\"margin-bottom: 6px !important;\">$dest</li>";
            }
            return '
<div style="font-family: \'Outfit\', sans-serif;">
    <p style="font-size: 1.05rem; line-height: 1.6; color: #475569; margin-bottom: 20px;">
        Jelajahi keindahan lereng Gunung Merapi dengan paket petualangan <strong>' . $packageName . '</strong>. Nikmati sensasi offroad menggunakan Jeep 4x4 klasik menyusuri rute bersejarah pasca-erupsi yang menakjubkan.
    </p>
    
    <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-left: 5px solid #22c55e; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
        <h4 style="font-weight: bold; color: #166534; margin: 0 0 8px 0; font-size: 1.1rem; display: flex; align-items: center; gap: 8px;">
            📋 Spesifikasi & Durasi Trip
        </h4>
        <ul style="list-style-type: none !important; margin: 0 !important; padding: 0 !important; font-size: 0.95rem; color: #14532d;">
            <li style="margin-bottom: 6px !important; display: flex; align-items: center; gap: 8px;">⏱️ <strong>Durasi Perjalanan:</strong> ' . $duration . '</li>
            <li style="margin-bottom: 0 !important; display: flex; align-items: center; gap: 8px;">👥 <strong>Kapasitas Penumpang:</strong> Maksimal 4 Orang per Jeep</li>
        </ul>
    </div>

    <div style="margin-bottom: 24px;">
        <h4 style="font-weight: bold; color: #1e293b; margin: 0 0 12px 0; font-size: 1.15rem; border-bottom: 2px solid #cbd5e1; padding-bottom: 6px;">
            📍 Destinasi yang Dikunjungi:
        </h4>
        <ul style="list-style-type: disc !important; padding-left: 20px !important; margin: 0 !important; font-size: 1rem; color: #334155; line-height: 1.6;">
            ' . $destList . '
        </ul>
    </div>

    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 20px; border-radius: 12px;">
        <h4 style="font-weight: bold; color: #0f172a; margin: 0 0 12px 0; font-size: 1.1rem; display: flex; align-items: center; gap: 8px;">
            ✨ Fasilitas yang Didapat (Include):
        </h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
            <div style="font-size: 0.95rem; color: #475569;">
                ✅ Driver as Guide & Dokumentasi Foto
            </div>
            <div style="font-size: 0.95rem; color: #475569;">
                ✅ Tiket Masuk Destinasi & BBM Jeep
            </div>
            <div style="font-size: 0.95rem; color: #475569;">
                ✅ Helm Keselamatan & Asuransi Jiwa
            </div>
            <div style="font-size: 0.95rem; color: #475569;">
                ✅ Jasa Freelance Fotografer
            </div>
        </div>
    </div>
</div>';
        };

        TourPackage::create([
            'title' => 'Paket Wisata Jeep Merapi - PAKET 1',
            'description' => $getMerapiDescription('PAKET 1', [
                'Batu Alien',
                'Museum Mini Sisa Hartaku',
                'Track Grogol Forest Hill',
                'Gapura The Lost World (Spot Foto)',
                'Kaliopak View Merapi (Spot Foto)',
                'Manuver Air Kalikuning'
            ], '1.5 Jam'),
            'price' => 450000,
            'price_unit' => 'unit',
            'duration' => '1.5 Jam',
            'main_image' => 'https://images.unsplash.com/photo-1626082896492-766af4fc6595?w=800'
        ]);

        TourPackage::create([
            'title' => 'Paket Wisata Jeep Merapi - PAKET SHORT PLUS A',
            'description' => $getMerapiDescription('PAKET SHORT PLUS A', [
                'Bunker Kaliadem',
                'Museum Mini Sisa Hartaku',
                'Track Grogol Forest Hill',
                'Gapura The Lost World (Spot Foto)',
                'Kaliopak View Merapi (Spot Foto)',
                'Manuver Air Kalikuning'
            ], '1.5 Jam'),
            'price' => 500000,
            'price_unit' => 'unit',
            'duration' => '1.5 Jam',
            'main_image' => 'https://images.unsplash.com/photo-1578301978018-3005759f48f7?w=800'
        ]);

        TourPackage::create([
            'title' => 'Paket Wisata Jeep Merapi - PAKET SHORT PLUS C',
            'description' => $getMerapiDescription('PAKET SHORT PLUS C', [
                'Bunker Kaliadem',
                'Batu Alien',
                'Track Grogol Forest Hill',
                'Gapura The Lost World (Spot Foto)',
                'Kaliopak View Merapi (Spot Foto)',
                'Manuver Air Kalikuning'
            ], '1.5 Jam'),
            'price' => 500000,
            'price_unit' => 'unit',
            'duration' => '1.5 Jam',
            'main_image' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=800'
        ]);

        TourPackage::create([
            'title' => 'Paket Wisata Jeep Merapi - PAKET 2 A',
            'description' => $getMerapiDescription('PAKET 2 A', [
                'Batu Alien',
                'Museum Mini Sisa Hartaku',
                'Bunker Kaliadem',
                'Track Grogol Forest Hill',
                'Gapura The Lost World (Spot Foto)',
                'Kaliopak View Merapi (Spot Foto)',
                'Manuver Air Kalikuning'
            ], '2 Jam'),
            'price' => 550000,
            'price_unit' => 'unit',
            'duration' => '2 Jam',
            'main_image' => 'https://images.unsplash.com/photo-1542382156909-9ae37b3f56fd?w=800'
        ]);

        TourPackage::create([
            'title' => 'Paket Wisata Jeep Merapi - PAKET 2 B',
            'description' => $getMerapiDescription('PAKET 2 B', [
                'Museum Mini Sisa Hartaku',
                'Bunker Kaliadem',
                'Petilasan Rumah Mbah Maridjan',
                'Track Grogol Forest Hill',
                'Gapura The Lost World (Spot Foto)',
                'Kaliopak View Merapi (Spot Foto)',
                'Manuver Air Kalikuning'
            ], '2 Jam'),
            'price' => 550000,
            'price_unit' => 'unit',
            'duration' => '2 Jam',
            'main_image' => 'https://images.unsplash.com/photo-1472214222555-d404758b1c42?w=800'
        ]);

        TourPackage::create([
            'title' => 'Paket Wisata Jeep Merapi - PAKET 2 E',
            'description' => $getMerapiDescription('PAKET 2 E', [
                'Bunker Kaliadem',
                'Tebing Gendol',
                'Batu Alien',
                'Track Grogol Forest Hill',
                'Gapura The Lost World (Spot Foto)',
                'Kaliopak View Merapi (Spot Foto)',
                'Manuver Air Kalikuning'
            ], '2 Jam'),
            'price' => 550000,
            'price_unit' => 'unit',
            'duration' => '2 Jam',
            'main_image' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800'
        ]);

        TourPackage::create([
            'title' => 'Paket Wisata Jeep Merapi - PAKET 3',
            'description' => $getMerapiDescription('PAKET 3', [
                'Batu Alien',
                'Museum Mini Sisa Hartaku',
                'Bunker Kaliadem',
                'Petilasan Rumah Mbah Maridjan',
                'Track Grogol Forest Hill',
                'Gapura The Lost World (Spot Foto)',
                'Kaliopak View Merapi (Spot Foto)',
                'Manuver Air Kalikuning'
            ], '3 Jam'),
            'price' => 650000,
            'price_unit' => 'unit',
            'duration' => '3 Jam',
            'main_image' => 'https://images.unsplash.com/photo-1482862549707-f63cb32c5fd9?w=800'
        ]);

        TourPackage::create([
            'title' => 'Paket Wisata Jeep Merapi - PAKET SUNRISE',
            'description' => $getMerapiDescription('PAKET SUNRISE', [
                'Batu Alien',
                'Museum Mini Sisa Hartaku',
                'Bunker Kaliadem (Spot Sunrise)',
                'Gapura The Lost World (Spot Foto)',
                'Kaliopak View Merapi (Spot Foto)',
                'Manuver Air Kalikuning'
            ], '3 Jam'),
            'price' => 650000,
            'price_unit' => 'unit',
            'duration' => '3 Jam',
            'main_image' => 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=800'
        ]);

        // 3b. Seed Bromo Jeep Package
        TourPackage::create([
            'title' => 'Paket Jeep Bromo - Bromo Explore SUNRISE',
            'description' => '<p>Rasakan petualangan legendaris mengeksplorasi keindahan alam kawah Gunung Bromo yang magis pada saat matahari terbit. Menggunakan transportasi Jeep 4x4 (Toyota Land Cruiser FJ40) tangguh, paket ini akan mengantar Anda menyusuri lautan pasir hingga puncak Penanjakan.</p>
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin: 16px 0;">
  <div style="background-color: #f1f5f9; padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1;">
    <h5 style="font-weight: bold; color: #1e293b; margin-bottom: 4px; font-size: 0.9rem;">Informasi Perjalanan:</h5>
    <ul style="list-style-type: none; padding-left: 0; font-size: 0.85rem; line-height: 1.5; color: #475569;">
      <li><strong>Kapasitas:</strong> Maks. 5-6 orang / Jeep</li>
      <li><strong>Waktu Operasional:</strong> 01.00 - 11.00 WIB</li>
      <li><strong>Titik Kumpul:</strong> Tumpang, Malang</li>
    </ul>
  </div>
  <div style="background-color: #f1f5f9; padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1;">
    <h5 style="font-weight: bold; color: #1e293b; margin-bottom: 4px; font-size: 0.9rem;">Fasilitas (Include):</h5>
    <ul style="list-style-type: none; padding-left: 0; font-size: 0.85rem; line-height: 1.5; color: #475569;">
      <li>✓ Sewa & Transportasi Jeep 4x4</li>
      <li>✓ Driver Jeep Profesional</li>
      <li>✓ Bahan Bakar Minyak (BBM)</li>
    </ul>
  </div>
</div>
<h4 style="font-weight: bold; color: var(--dark); margin-top: 16px; margin-bottom: 8px;">Rute & Destinasi Wisata:</h4>
<ol style="padding-left: 20px; margin-bottom: 16px; line-height: 1.5;">
  <li><strong>View Sunrise Point</strong> (Menikmati matahari terbit terindah)</li>
  <li><strong>Lembah Widodaren</strong> (Dinding batuan eksotis)</li>
  <li><strong>Kawah Bromo</strong> (Mendaki ke bibir kawah aktif)</li>
  <li><strong>Pura Luhur Poten</strong> (Pura suci suku Tengger di kaki Bromo)</li>
  <li><strong>Pasir Berbisik</strong> (Lautan pasir luas berbunyi desis)</li>
  <li><strong>Padang Savana & Bukit Teletubbies</strong> (Hamparan bukit hijau subur)</li>
</ol>
<h4 style="font-weight: bold; color: var(--dark); margin-top: 16px; margin-bottom: 8px;">Belum Termasuk (Exclude):</h4>
<p style="margin-bottom: 12px;">Harga sewa jeep di atas <strong>belum termasuk tiket masuk</strong> Taman Nasional Bromo Tengger Semeru (TNBTS). Berikut rincian harga tiket per orang yang perlu Anda siapkan:</p>
<div style="overflow-x: auto;">
  <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.85rem; border: 1px solid #e2e8f0; margin-bottom: 16px;">
    <thead>
      <tr style="background-color: #f8fafc; border-bottom: 2px solid #cbd5e1;">
        <th style="padding: 10px; border: 1px solid #e2e8f0; font-weight: bold; color: var(--dark);">Kategori Hari</th>
        <th style="padding: 10px; border: 1px solid #e2e8f0; color: var(--dark);">Harga Tiket Masuk (Per Orang)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="padding: 8px; border: 1px solid #e2e8f0; font-weight: 500;">Hari Kerja (Weekday - Senin s/d Jumat)</td>
        <td style="padding: 8px; border: 1px solid #e2e8f0; font-weight: bold; color: var(--secondary);">Rp 54.000</td>
      </tr>
      <tr>
        <td style="padding: 8px; border: 1px solid #e2e8f0; font-weight: 500;">Hari Libur (Weekend - Sabtu, Minggu & Tanggal Merah)</td>
        <td style="padding: 8px; border: 1px solid #e2e8f0; font-weight: bold; color: var(--secondary);">Rp 79.000</td>
      </tr>
    </tbody>
  </table>
</div>',
            'price' => 900000,
            'price_unit' => 'unit',
            'duration' => '01.00 - 11.00 WIB',
            'main_image' => 'https://images.unsplash.com/photo-1602148740250-0a4750e232e9?w=800'
        ]);

        // 3c. Seed Jogja Regular Packages
        $getRegularDescription = function ($packageName, $destinations) {
            $destList = '';
            foreach ($destinations as $dest) {
                $destList .= "<li style=\"margin-bottom: 6px !important;\">$dest</li>";
            }
            return '
<div style="font-family: \'Outfit\', sans-serif;">
    <p style="font-size: 1.05rem; line-height: 1.6; color: #475569; margin-bottom: 20px;">
        Nikmati keseruan liburan di Yogyakarta dengan paket private trip eksklusif <strong>' . $packageName . '</strong>. Layanan sewa mobil + driver + bensin siap mengantar Anda berkeliling destinasi terbaik Jogja dengan nyaman.
    </p>
    
    <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-left: 5px solid #22c55e; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
        <h4 style="font-weight: bold; color: #166534; margin: 0 0 8px 0; font-size: 1.1rem; display: flex; align-items: center; gap: 8px;">
            📋 Spesifikasi & Ketentuan Trip
        </h4>
        <ul style="list-style-type: none !important; margin: 0 !important; padding: 0 !important; font-size: 0.95rem; color: #14532d;">
            <li style="margin-bottom: 6px !important; display: flex; align-items: center; gap: 8px;">⏱️ <strong>Durasi Sewa:</strong> 12 Jam per Hari</li>
            <li style="margin-bottom: 0 !important; display: flex; align-items: center; gap: 8px;">👥 <strong>Kapasitas:</strong> Maksimal 5-6 Orang per Mobil</li>
        </ul>
    </div>

    <div style="margin-bottom: 24px;">
        <h4 style="font-weight: bold; color: #1e293b; margin: 0 0 12px 0; font-size: 1.15rem; border-bottom: 2px solid #cbd5e1; padding-bottom: 6px;">
            📍 Destinasi yang Dikunjungi:
        </h4>
        <ul style="list-style-type: disc !important; padding-left: 20px !important; margin: 0 !important; font-size: 1rem; color: #334155; line-height: 1.6;">
            ' . $destList . '
        </ul>
    </div>

    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 20px; border-radius: 12px;">
        <h4 style="font-weight: bold; color: #0f172a; margin: 0 0 12px 0; font-size: 1.1rem; display: flex; align-items: center; gap: 8px;">
            ✨ Fasilitas yang Didapat (Include):
        </h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
            <div style="font-size: 0.95rem; color: #475569;">
                ✅ Transportasi Mobil AC Bersih
            </div>
            <div style="font-size: 0.95rem; color: #475569;">
                ✅ Supir / Driver Ramah & Berpengalaman
            </div>
            <div style="font-size: 0.95rem; color: #475569;">
                ✅ Bahan Bakar Minyak (BBM)
            </div>
        </div>
    </div>
</div>';
        };

        $regularPackages = [
            ['packageName' => 'Paket 1', 'price' => 700000, 'destinations' => ['Candi Borobudur', 'Lava Tour Merapi', 'Candi Prambanan', 'Candi Ratu Boko', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket 2', 'price' => 700000, 'destinations' => ['Candi Borobudur', 'Vw Borobudur', 'Lava Tour Merapi', 'Bhumi Merapi', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket 3', 'price' => 650000, 'destinations' => ['Lava Tour Merapi', 'Museum Ulen Sentalu', 'The Nice Playland', 'Suraloka Zoo', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket 4', 'price' => 650000, 'destinations' => ['Keraton Yogyakarta', 'Taman Sari', 'Museum Sasonobudoyo', 'Ibardo Park', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket 5', 'price' => 650000, 'destinations' => ['Candi Prambanan', 'Candi Ratu Boko', 'Tebing Breksi', 'Heha Sky View', 'Pictniq Land', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket 6', 'price' => 700000, 'destinations' => ['Candi Prambanan', 'Obelix Hills', 'Hutan Pinus Pengger', 'Hutan Pinus Becici', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket 7', 'price' => 700000, 'destinations' => ['Tumpeng Menoreh', 'Omah Cantrik', 'Studi Alam Gamplong', 'Benteng Vredeburg', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket 8', 'price' => 700000, 'destinations' => ['Pantai Ngobaran', 'Pantai Ngrenegan', 'Obelix Sea View', 'Gumuk Pasir', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket 9', 'price' => 700000, 'destinations' => ['Goa Pindul', 'Goa Jimblang', 'Pantai Timang', 'Drini Park', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket 10', 'price' => 700000, 'destinations' => ['Goa Pindul', 'Pantai Mesra', 'Pantai Drini', 'On The Rock', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket 11', 'price' => 700000, 'destinations' => ['Puncak Segoro', 'Heha Ocean View', 'Gesing Wonderland', 'Obelix Sea View', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket 12', 'price' => 850000, 'destinations' => ['Nepal Van Java', 'Negri Sayur Sukamakmur', 'Vw Safari Borobudur', 'Candi Borobudur', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket 13', 'price' => 1100000, 'destinations' => ['Kawah Sikidang Dieng', 'Candi Arjuna', 'Batu Ratapan Angin', 'Taman Langit', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket 14', 'price' => 700000, 'destinations' => ['Lava Tour Merapi', 'Kopi Klothok', 'Chandari Heaven', 'Candi Prambanan', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket 15', 'price' => 650000, 'destinations' => ['Keraton Yogyakarta', 'Taman Sari', 'Pantai Parangtritis', 'Obelix Sea', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket 16', 'price' => 700000, 'destinations' => ['Puncak Becici', 'Sri Gethuk', 'Pantai Mesra', 'Pantai Mbuluk', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket 17', 'price' => 700000, 'destinations' => ['Candi Borobudur', 'Jeep Merapi', 'Ibarbo', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket 18', 'price' => 750000, 'destinations' => ['STD Gamplong', 'Geblek Pari', 'Tumpeng Menoreh', 'Pule Payung', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket 19', 'price' => 750000, 'destinations' => ['Mangunan', 'On The Rock', 'Puncak Segoro', 'Obelix Sea', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket 20', 'price' => 750000, 'destinations' => ['Pantai Timang', 'Pantai Jungwok', 'Heha Sky', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket 21', 'price' => 750000, 'destinations' => ['Pantai Timang', 'Pantai Jungwok', 'Heha Sky', 'Pusat Oleh-oleh']],
        ];

        foreach ($regularPackages as $p) {
            TourPackage::create([
                'title' => 'Paket Jogja Reguler - ' . $p['packageName'],
                'description' => $getRegularDescription($p['packageName'], $p['destinations']),
                'price' => $p['price'],
                'price_unit' => 'unit',
                'duration' => '12 Jam',
                'main_image' => 'https://images.unsplash.com/photo-1584824486509-112e4181ff6b?w=800'
            ]);
        }

        // 3d. Seed Jogja Sunrise Packages
        $sunrisePackages = [
            ['packageName' => 'Paket SUNRISE 1', 'price' => 750000, 'destinations' => ['Jeep Merapi', 'Candi Prambanan', 'Pictniq', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket SUNRISE 2', 'price' => 950000, 'destinations' => ['Silancur', 'Nepal Van Java', 'Borobudur', 'Pusat Oleh-oleh']],
            ['packageName' => 'Paket SUNRISE 3', 'price' => 1400000, 'destinations' => ['Sunrise Taman Langit', 'Batu Ratapan Angin', 'Kawab Sikidang', 'Kebun Teh Panama', 'Telaga Menjer', 'Pusat Oleh-oleh']],
        ];

        foreach ($sunrisePackages as $p) {
            TourPackage::create([
                'title' => 'Paket Jogja Sunrise - ' . $p['packageName'],
                'description' => $getRegularDescription($p['packageName'], $p['destinations']),
                'price' => $p['price'],
                'price_unit' => 'unit',
                'duration' => '12 Jam',
                'main_image' => 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=800'
            ]);
        }

        // 3e. Seed Sewa Armada Packages
        $fleetRentals = [
            ['vehicleName' => 'Calya LCGC', 'price' => 650000, 'duration' => '12 Jam'],
            ['vehicleName' => 'Avanza', 'price' => 750000, 'duration' => '12 Jam'],
            ['vehicleName' => 'Inova Reborn', 'price' => 1350000, 'duration' => '12 Jam'],
            ['vehicleName' => 'Haice', 'price' => 1450000, 'duration' => '12 Jam'],
            ['vehicleName' => 'Haice Pemio', 'price' => 1600000, 'duration' => '12 Jam'],
        ];

        foreach ($fleetRentals as $f) {
            TourPackage::create([
                'title' => 'Sewa Armada - ' . $f['vehicleName'],
                'description' => '<div style="font-family: \'Outfit\', sans-serif;"><p>Sewa mobil ' . $f['vehicleName'] . ' untuk perjalanan bisnis, liburan keluarga, atau keperluan pribadi di area Yogyakarta. Armada bersih, terawat, dan siap pakai.</p><p><strong>Termasuk:</strong> Mobil + Supir + BBM.</p></div>',
                'price' => $f['price'],
                'price_unit' => 'unit',
                'duration' => $f['duration'],
                'main_image' => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=800'
            ]);
        }

        // 3f. Seed Airport Transfer Packages
        $airportTransfers = [
            ['name' => 'PAKET ANTAR BANDARA (DROP)', 'price' => 300000, 'duration' => 'Sekali Jalan'],
            ['name' => 'PAKET JEMPUT BANDARA (PICKUP)', 'price' => 350000, 'duration' => 'Sekali Jalan'],
        ];

        foreach ($airportTransfers as $a) {
            TourPackage::create([
                'title' => 'Airport Transfer - ' . $a['name'],
                'description' => '<div style="font-family: \'Outfit\', sans-serif;"><p>Layanan transfer bandara dari/ke Bandara Internasional Yogyakarta (YIA) ke semua wilayah Yogyakarta. Nyaman dan bebas repot.</p><p><strong>Termasuk:</strong> Mobil + Supir + BBM.</p></div>',
                'price' => $a['price'],
                'price_unit' => 'unit',
                'duration' => $a['duration'],
                'main_image' => 'https://images.unsplash.com/photo-1436491865332-7a615061c443?w=800'
            ]);
        }

        // 3g. Seed Bali Rentcar Packages
        // Bali Lepas Kunci
        $baliLepasKunci = [
            ['vehicleName' => 'Inova Reborn Matic', 'price' => 600000, 'transmission' => 'Matic'],
            ['vehicleName' => 'Avanza Matic', 'price' => 450000, 'transmission' => 'Matic'],
            ['vehicleName' => 'Avanza Manual', 'price' => 400000, 'transmission' => 'Manual'],
            ['vehicleName' => 'Agya Matic', 'price' => 400000, 'transmission' => 'Matic'],
            ['vehicleName' => 'Brio Matic', 'price' => 450000, 'transmission' => 'Matic'],
        ];

        foreach ($baliLepasKunci as $b) {
            TourPackage::create([
                'title' => 'Bali Lepas Kunci - ' . $b['vehicleName'],
                'description' => '<div style="font-family: \'Outfit\', sans-serif;"><p>Sewa mobil lepas kunci di Bali dengan armada ' . $b['vehicleName'] . ' (' . $b['transmission'] . '). Nikmati kebebasan mengeksplorasi Pulau Dewata.</p><p><strong>Ketentuan:</strong> KTP/Paspor asli & SIM A.</p><p><strong>Overtime:</strong> Rp 50.000 / jam</p></div>',
                'price' => $b['price'],
                'price_unit' => 'unit',
                'duration' => '24 Jam',
                'main_image' => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=800'
            ]);
        }

        // Bali All In
        $baliAllIn = [
            ['vehicleName' => 'Inova Reborn', 'price' => 1100000],
            ['vehicleName' => 'Avanza', 'price' => 900000],
            ['vehicleName' => 'Toyota Hiace', 'price' => 1600000],
        ];

        foreach ($baliAllIn as $b) {
            TourPackage::create([
                'title' => 'Bali All In - ' . $b['vehicleName'] . ' (Driver + BBM)',
                'description' => '<div style="font-family: \'Outfit\', sans-serif;"><p>Sewa mobil dengan Driver + BBM di Bali dengan armada ' . $b['vehicleName'] . '. Santai dan nikmati perjalanan tanpa harus menyetir sendiri.</p><p><strong>Overtime:</strong> 10% per jam dari tarif sewa</p></div>',
                'price' => $b['price'],
                'price_unit' => 'unit',
                'duration' => '10 Jam',
                'main_image' => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=800'
            ]);
        }

        // Bali Motor
        $baliMotor = [
            ['vehicleName' => 'NMAX', 'price' => 175000],
            ['vehicleName' => 'Vario 125', 'price' => 125000],
            ['vehicleName' => 'Fazzio', 'price' => 125000],
            ['vehicleName' => 'Beat', 'price' => 125000],
            ['vehicleName' => 'Scoopy', 'price' => 125000],
        ];

        foreach ($baliMotor as $b) {
            TourPackage::create([
                'title' => 'Bali Motor - Sewa Motor ' . $b['vehicleName'],
                'description' => '<div style="font-family: \'Outfit\', sans-serif;"><p>Sewa motor ' . $b['vehicleName'] . ' di Bali untuk menghindari kemacetan dan kepraktisan berkendara di area Kuta, Seminyak, Canggu, atau Ubud.</p><p><strong>Termasuk:</strong> 2 Helm SNI + 2 Jas Hujan.</p></div>',
                'price' => $b['price'],
                'price_unit' => 'unit',
                'duration' => '24 Jam',
                'main_image' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=800'
            ]);
        }

        // 4. Seed Destinations
        Destination::create([
            'category_id' => $alam->id,
            'title' => 'Bunker Kaliadem Merapi',
            'slug' => 'bunker-kaliadem-merapi',
            'excerpt' => 'Bunker bersejarah di lereng Merapi yang menawarkan pemandangan gagah puncak gunung.',
            'description' => 'Bunker Kaliadem adalah ruang bawah tanah yang dibangun khusus sebagai tempat perlindungan darurat dari awan panas letusan Gunung Merapi. Terletak di Sleman, tempat ini kini menjadi destinasi wisata favorit yang memadukan sejarah saksi bisu erupsi dahsyat dengan pemandangan langsung puncak Merapi yang berjarak sangat dekat.',
            'location' => 'Sleman, Yogyakarta',
            'featured' => true,
            'main_image' => 'https://images.unsplash.com/photo-1626082896492-766af4fc6595?w=800'
        ]);

        Destination::create([
            'category_id' => $alam->id,
            'title' => 'Sunrise Point Penanjakan Bromo',
            'slug' => 'sunrise-point-penanjakan-bromo',
            'excerpt' => 'Titik pandang terbaik untuk menyaksikan matahari terbit magis berlatar kaldera Gunung Bromo.',
            'description' => 'Penanjakan 1 adalah bukit tertinggi di kawasan Bromo Tengger Semeru yang menjadi lokasi paling legendaris untuk menyaksikan matahari terbit (sunrise). Dari sini, wisatawan dapat melihat panorama kawah Bromo berselimut kabut pagi bagaikan negeri di atas awan.',
            'location' => 'Pasuruan, Jawa Timur',
            'featured' => true,
            'main_image' => 'https://images.unsplash.com/photo-1588668214407-6797208c9012?w=800'
        ]);

        Destination::create([
            'category_id' => $budaya->id,
            'title' => 'Museum Mini Sisa Hartaku',
            'slug' => 'museum-mini-sisa-hartaku',
            'excerpt' => 'Museum peringatan berisi barang-barang rumah tangga yang meleleh akibat awan panas Merapi.',
            'description' => 'Museum Sisa Hartaku adalah sebuah museum kecil milik warga lokal yang didirikan untuk memperingati erupsi Gunung Merapi tahun 2010. Di dalam museum ini dipajang berbagai sisa harta benda warga yang hancur dan meleleh akibat awan panas, mulai dari sepeda motor, peralatan dapur, hingga jam dinding yang berhenti tepat saat awan panas menerjang.',
            'location' => 'Sleman, Yogyakarta',
            'featured' => true,
            'main_image' => 'https://images.unsplash.com/photo-1616788494707-ec28f08d05a1?w=800'
        ]);

        Destination::create([
            'category_id' => $budaya->id,
            'title' => 'Pura Luhur Poten Bromo',
            'slug' => 'pura-luhur-poten-bromo',
            'excerpt' => 'Pura Hindu suci milik suku Tengger yang berdiri kokoh di tengah hamparan Lautan Pasir Bromo.',
            'description' => 'Pura Luhur Poten adalah tempat ibadah suci bagi umat Hindu suku Tengger yang bertempat tinggal di sekitar Gunung Bromo. Pura ini memiliki arsitektur khas Jawa-Bali dan berdiri sangat unik di tengah hamparan pasir vulkanik (Lautan Pasir) Gunung Bromo.',
            'location' => 'Probolinggo, Jawa Timur',
            'featured' => true,
            'main_image' => 'https://images.unsplash.com/photo-1542382156909-9ae37b3f56fd?w=800'
        ]);

        Destination::create([
            'category_id' => $alam->id,
            'title' => 'Lautan Pasir Berbisik Bromo',
            'slug' => 'lautan-pasir-berbisik-bromo',
            'excerpt' => 'Hamparan pasir vulkanik hitam luas yang menghasilkan suara desisan unik saat tertiup angin.',
            'description' => 'Pasir Berbisik adalah sebutan untuk kawasan lautan pasir luas berwarna kehitaman yang membentang di sekitar kaldera Gunung Bromo. Dinamakan Pasir Berbisik karena ketika angin berembus kencang, gesekan butiran pasir mengeluarkan suara desisan lembut menyerupai bisikan.',
            'location' => 'Probolinggo, Jawa Timur',
            'featured' => false,
            'main_image' => 'https://images.unsplash.com/photo-1601918774946-25832a4be0d6?w=800'
        ]);

        Destination::create([
            'category_id' => $alam->id,
            'title' => 'Savana & Bukit Teletubbies Bromo',
            'slug' => 'savana-bukit-teletubbies-bromo',
            'excerpt' => 'Padang rumput hijau subur dan perbukitan meliuk yang kontras dengan lautan pasir Bromo.',
            'description' => 'Padang Savana Bromo adalah sebuah lembah hijau subur berpagar dinding kaldera raksasa di sisi selatan Gunung Bromo. Di tengah savana ini terdapat deretan perbukitan meliuk hijau yang mirip dengan lokasi film anak-anak Teletubbies.',
            'location' => 'Malang, Jawa Timur',
            'featured' => false,
            'main_image' => 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=800'
        ]);

        Destination::create([
            'category_id' => $kuliner->id,
            'title' => 'Jadah Tempe Mbah Carik Kaliurang',
            'slug' => 'jadah-tempe-mbah-carik',
            'excerpt' => 'Kuliner legendaris khas lereng Merapi berupa perpaduan ketan gurih dan tempe bacem manis.',
            'description' => 'Jadah Tempe Mbah Carik adalah kuliner tradisional legendaris asal Kaliurang, Sleman yang sudah ada sejak tahun 1950-an. Kuliner ini merupakan perpaduan unik antara Jadah (ketan gurih) dan Tempe Bacem manis gurih yang dimakan secara bersamaan.',
            'location' => 'Sleman, Yogyakarta',
            'featured' => false,
            'main_image' => 'https://images.unsplash.com/photo-1605333396915-47ed6b68a00e?w=800'
        ]);

        Destination::create([
            'category_id' => $kuliner->id,
            'title' => 'Bakso & Bakwan Malang Klasik',
            'slug' => 'bakso-bakwan-malang-klasik',
            'excerpt' => 'Kehangatan semangkuk bakso kuah kaldu sapi pekat lengkap dengan pangsit goreng dan siomay basah.',
            'description' => 'Bakso Malang adalah kuliner ikonik khas Malang yang terkenal dengan kelengkapannya berupa bakso halus, siomay basah, tahu bakso, serta pangsit goreng renyah, disiram kuah kaldu sapi gurih yang cocok dinikmati di tengah hawa dingin Bromo.',
            'location' => 'Malang, Jawa Timur',
            'featured' => false,
            'main_image' => 'https://images.unsplash.com/photo-1585032226651-759b368d7246?w=800'
        ]);

        Destination::create([
            'category_id' => $bahari->id,
            'title' => 'Pantai Sadranan Gunungkidul',
            'slug' => 'pantai-sadranan-gunungkidul',
            'excerpt' => 'Pantai pasir putih berombak tenang yang populer sebagai lokasi snorkeling terbaik di Jogja.',
            'description' => 'Pantai Sadranan adalah salah satu destinasi wisata bahari unggulan di Yogyakarta yang terkenal dengan pasir putih bersih dan air laut yang sangat jernih. Pantai ini dilindungi karang alami sehingga aman untuk aktivitas snorkeling.',
            'location' => 'Gunungkidul, Yogyakarta',
            'featured' => false,
            'main_image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800'
        ]);

        Destination::create([
            'category_id' => $bahari->id,
            'title' => 'Pantai Balekambang Malang',
            'slug' => 'pantai-balekambang-malang',
            'excerpt' => 'Keindahan pantai pasir putih dengan pura megah di atas pulau karang mirip Tanah Lot.',
            'description' => 'Pantai Balekambang adalah pantai eksotis di pesisir selatan Kabupaten Malang. Daya tarik utama pantai ini adalah keberadaan Pura Amarta Jati yang berdiri megah di atas Pulau Ismoyo karang di tengah pantai.',
            'location' => 'Malang, Jawa Timur',
            'featured' => false,
            'main_image' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=800'
        ]);

        // 5. Seed Testimonials
        Testimonial::create([
            'name' => 'Ferry Setiawan',
            'profession' => 'Karyawan Swasta',
            'quote' => 'Pelayanan KawanJalan Tour & Travel sangat luar biasa! Proses booking paket Bali sangat cepat, sistem DP 30% via website sangat membantu cashflow liburan keluarga kami. E-Tiket langsung kami terima setelah status di-update admin.',
            'rating' => 5,
            'image' => 'testimonial_ferry.jpg'
        ]);

        Testimonial::create([
            'name' => 'Dian Sastrowardoyo',
            'profession' => 'Content Creator & Photographer',
            'quote' => 'Sangat suka dengan detail informasi destinasi wisata yang ada di KawanJalan Tour & Travel. Selain itu, pendaftaran event kebudayaannya sangat mudah dilakukan lewat handphone. Desain websitenya clean dan modern!',
            'rating' => 5,
            'image' => 'testimonial_dian.jpg'
        ]);

        // 6. Seed Events
        Event::create([
            'title' => 'Bali Arts Festival (Pesta Kesenian Bali) 2026',
            'slug' => 'bali-arts-festival-2026',
            'description' => 'Parade budaya tahunan terbesar di Bali yang menampilkan tari-tarian kolosal, pameran kerajinan tangan khas kabupaten/kota se-Bali, pertunjukan gamelan gong kebyar, serta sendratari Ramayana di panggung Terbuka Ardha Candra.',
            'main_image' => 'event_bali_arts.jpg',
            'date' => now()->addDays(20),
            'location' => 'Taman Budaya Art Center, Denpasar'
        ]);

        // Expired Event to test validation
        Event::create([
            'title' => 'Borobudur Marathon & Festival Kebudayaan 2026',
            'slug' => 'borobudur-marathon-2026',
            'description' => 'Event lari maraton berskala internasional yang melintasi pedesaan asri di sekitar kawasan Candi Borobudur, diselingi pertunjukan tari tradisional khas Magelang di sepanjang rute pelari.',
            'main_image' => 'event_borobudur_run.jpg',
            'date' => now()->subDays(5),
            'location' => 'Kawasan Candi Borobudur, Magelang'
        ]);

        // 7. Seed Articles & Article Images
        $article1 = Article::create([
            'title' => 'Panduan Lengkap Berburu Sunrise Magis di Gunung Bromo',
            'slug' => 'panduan-berburu-sunrise-magis-gunung-bromo',
            'excerpt' => 'Rekomendasi spot sunrise terbaik di Bromo, tips berpakaian hangat, hingga panduan sewa Jeep 4x4.',
            'description' => 'Gunung Bromo selalu menyajikan pemandangan sunrise yang luar biasa megah dan menarik ribuan wisatawan setiap tahunnya. Petualangan berburu sunrise biasanya dimulai sejak dini hari pukul 02.00 WIB. Pilihan transportasi utama adalah Jeep 4x4 yang tangguh menerjang lautan pasir berliku. Ada beberapa spot pandang terbaik yang bisa Anda pilih, seperti Penanjakan 1, Bukit Cinta, Bukit Kedaluh (King Kong Hill), atau Bukit Mentigen. Untuk menikmati petualangan ini dengan maksimal, pastikan Anda mempersiapkan pakaian hangat yang tebal seperti jaket gunung, kupluk penutup kepala, sarung tangan, dan masker pelindung debu pasir.',
            'main_image' => 'https://images.unsplash.com/photo-1588668214407-6797208c9012?w=800'
        ]);

        ArticleImage::create([
            'article_id' => $article1->id,
            'image_name' => 'https://images.unsplash.com/photo-1602148740250-0a4750e232e9?w=800'
        ]);
        ArticleImage::create([
            'article_id' => $article1->id,
            'image_name' => 'https://images.unsplash.com/photo-1601918774946-25832a4be0d6?w=800'
        ]);

        $article2 = Article::create([
            'title' => 'Menyusuri Jejak Sejarah & Memacu Adrenalin Lavatour Merapi',
            'slug' => 'menyusuri-jejak-sejarah-adrenalin-lavatour-merapi',
            'excerpt' => 'Kisah saksi bisu erupsi besar Merapi yang kini menjelma menjadi rute petualangan jeep offroad menantang.',
            'description' => 'Lavatour Merapi menawarkan kombinasi unik antara wisata sejarah mitigasi bencana dan petualangan memacu adrenalin. Mengendarai Jeep Willys klasik tanpa atap, Anda akan diajak menyusuri kawasan lereng selatan yang terdampak langsung erupsi dahsyat tahun 2010. Perjalanan mencakup kunjungan ke Bunker Kaliadem, Museum Sisa Hartaku yang mengoleksi barang rumah tangga meleleh, serta Batu Alien. Petualangan ditutup dengan manuver air yang menegangkan di Kali Kuning. Siapkan kacamata hitam dan masker agar terhindar dari debu trek pasir vulkanik.',
            'main_image' => 'https://images.unsplash.com/photo-1626082896492-766af4fc6595?w=800'
        ]);

        ArticleImage::create([
            'article_id' => $article2->id,
            'image_name' => 'https://images.unsplash.com/photo-1616788494707-ec28f08d05a1?w=800'
        ]);
        ArticleImage::create([
            'article_id' => $article2->id,
            'image_name' => 'https://images.unsplash.com/photo-1578301978018-3005759f48f7?w=800'
        ]);
    }
}
