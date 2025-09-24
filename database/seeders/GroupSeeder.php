<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat Grup Induk
        $parentIT = Group::create([
            'name' => 'Helpdesk IT',
            'email' => 'helpdesk-it@app.com',
        ]);

        $parentBiro = Group::create([
            'name' => 'Biro Umum',
            'email' => 'biro-umum@app.com',
        ]);

        // Buat Grup Anak
        Group::create([
            'name' => 'Layanan',
            'email' => 'layanan@app.com',
            'parent_id' => $parentIT->id,
        ]);
        Group::create([
            'name' => 'NOC',
            'email' => 'noc@app.com',
            'parent_id' => $parentIT->id,
        ]);
        Group::create([
            'name' => 'SDM Teknisi',
            'email' => 'sdm-teknisi@app.com',
            'parent_id' => $parentBiro->id,
        ]);
    }
}
