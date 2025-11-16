<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'SOP (Standard Operating Procedure)', 'code' => 'SPO'],
            ['name' => 'Surat Keputusan', 'code' => 'SK'],
            ['name' => 'Laporan Bulanan', 'code' => 'LB'],
            ['name' => 'Dokumen Pelatihan Staf', 'code' => 'DPS'],
            ['name' => 'Surat Tugas', 'code' => 'ST'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
