<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Batik Tulis',
            'Batik Cap',
            'Batik Printing',
            'Kemeja Pria',
            'Kemeja Wanita',
            'Kain Motif Modern',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
