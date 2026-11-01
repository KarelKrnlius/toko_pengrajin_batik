<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'name');

        $products = [
            [
                'category' => 'Batik Tulis',
                'name'     => 'Batik Tulis Parang Kusumo',
                'price'    => 450000,
                'stock'    => 15,
                'desc'     => 'Batik tulis motif Parang Kusumo yang dibuat dengan tangan menggunakan canting tradisional. Bahan premium sutra ATBM dengan pewarna alami indigo dan soga. Ukuran 2,5m × 1,1m. Cocok untuk acara formal dan pernikahan adat Jawa.',
            ],
            [
                'category' => 'Batik Tulis',
                'name'     => 'Batik Tulis Kawung Sido Luhur',
                'price'    => 575000,
                'stock'    => 8,
                'desc'     => 'Motif kawung klasik yang melambangkan kesempurnaan dan keseimbangan hidup. Dibuat oleh pengrajin generasi ke-4 dari Kotagede, Yogyakarta. Bahan katun primissima, pewarna alam kulit kayu jati. Ukuran 2m × 1,1m.',
            ],
            [
                'category' => 'Batik Cap',
                'name'     => 'Batik Cap Truntum Klasik',
                'price'    => 185000,
                'stock'    => 40,
                'desc'     => 'Motif Truntum yang penuh makna kasih sayang menggunakan teknik cap (stempel tembaga). Proses pewarnaan dengan bahan sintetis berkualitas tinggi yang tahan luntur. Bahan katun combed 30s. Ukuran 2m × 1,1m.',
            ],
            [
                'category' => 'Batik Cap',
                'name'     => 'Batik Cap Sidomukti Sogan',
                'price'    => 210000,
                'stock'    => 25,
                'desc'     => 'Sidomukti adalah simbol kemakmuran dan keselamatan. Warna sogan (cokelat keemasan khas Solo) yang hangat cocok untuk berbagai acara. Teknik cap tembaga, bahan katun prima, ukuran 2,5m × 1,1m.',
            ],
            [
                'category' => 'Kemeja Pria',
                'name'     => 'Kemeja Batik Pria Mega Mendung',
                'price'    => 275000,
                'stock'    => 30,
                'desc'     => 'Kemeja pria lengan panjang dengan motif Mega Mendung khas Cirebon yang ikonik. Bahan katun poplin yang adem dan tidak kusut. Tersedia ukuran S, M, L, XL, XXL. Cocok untuk formal maupun semi-formal.',
            ],
            [
                'category' => 'Kemeja Pria',
                'name'     => 'Kemeja Batik Pria Sekar Jagad',
                'price'    => 295000,
                'stock'    => 22,
                'desc'     => 'Sekar Jagad berarti "bunga dunia", melambangkan keberagaman budaya Nusantara. Kemeja lengan pendek dengan desain kontemporer. Bahan tencel yang lembut dan breathable. Ukuran S hingga XXL.',
            ],
            [
                'category' => 'Kemeja Wanita',
                'name'     => 'Blouse Batik Wanita Lereng',
                'price'    => 320000,
                'stock'    => 18,
                'desc'     => 'Blouse wanita elegan dengan motif Lereng diagonal yang khas. Potongan A-line yang flatter untuk semua bentuk tubuh. Bahan rayon batik printing berkualitas. Tersedia dalam pilihan warna cokelat soga dan biru indigo.',
            ],
            [
                'category' => 'Kemeja Wanita',
                'name'     => 'Tunik Batik Wanita Pekalongan',
                'price'    => 345000,
                'stock'    => 12,
                'desc'     => 'Tunik panjang dengan motif bunga batik Pekalongan yang colorful dan ceria. Desain modern dengan kerah V-neck dan belahan samping. Bahan katun slub yang nyaman dipakai seharian. Ukuran S-XXL.',
            ],
            [
                'category' => 'Batik Printing',
                'name'     => 'Kain Batik Printing Lasem Merah',
                'price'    => 120000,
                'stock'    => 60,
                'desc'     => 'Reproduksi motif Batik Lasem dengan warna merah khas yang berani menggunakan teknologi printing modern. Bahan katun Paris yang ringan dan halus. Cocok untuk bahan baju, taplak, atau dekorasi. Per meter.',
            ],
            [
                'category' => 'Kain Motif Modern',
                'name'     => 'Kain Batik Kontemporer Abstrak',
                'price'    => 165000,
                'stock'    => 35,
                'desc'     => 'Perpaduan motif batik tradisional dengan sentuhan desain kontemporer. Cocok untuk generasi muda yang ingin tampil modern namun tetap mencintai budaya lokal. Bahan viscose lembut, ukuran 2m × 1,1m.',
            ],
            [
                'category' => 'Batik Tulis',
                'name'     => 'Batik Tulis Nitik Grompol',
                'price'    => 680000,
                'stock'    => 5,
                'desc'     => 'Grompol berarti berkumpul/bersatu. Batik tulis nitik paling langka karena dikerjakan titik demi titik dengan canting halus. Proses pengerjaan 2–3 bulan. Bahan sutra ATBM premium. Koleksi eksklusif Juragan Batik.',
            ],
            [
                'category' => 'Batik Cap',
                'name'     => 'Sarung Batik Cap Pria Klasik',
                'price'    => 135000,
                'stock'    => 45,
                'desc'     => 'Sarung batik cap untuk pria dengan motif klasik sogan Solo. Bahan katun combed berkualitas yang nyaman dipakai untuk sholat maupun aktivitas sehari-hari. Ukuran standar 115cm × 200cm.',
            ],
        ];

        foreach ($products as $data) {
            $catId = $categories[$data['category']] ?? null;
            if (! $catId) continue;

            Product::firstOrCreate(
                ['name' => $data['name']],
                [
                    'category_id' => $catId,
                    'price'       => $data['price'],
                    'stock'       => $data['stock'],
                    'description' => $data['desc'],
                    'image'       => null,
                ]
            );
        }
    }
}
