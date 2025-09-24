<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Kategori Induk
        $hardware = Category::create(['name' => 'Hardware']);
        $software = Category::create(['name' => 'Software']);
        $network = Category::create(['name' => 'Jaringan']);

        // Kategori Anak
        Category::create(['name' => 'Printer', 'parent_id' => $hardware->id]);
        Category::create(['name' => 'Komputer/Laptop', 'parent_id' => $hardware->id]);

        Category::create(['name' => 'Aplikasi A', 'parent_id' => $software->id]);
        Category::create(['name' => 'Sistem Operasi', 'parent_id' => $software->id]);
    }
}
