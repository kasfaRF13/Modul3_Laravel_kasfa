<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create(['code' => 'UMUM', 'name' => 'Umum']);
        Category::create(['code' => 'SEM', 'name' => 'Seminar']);
        Category::create(['code' => 'WS', 'name' => 'Workshop']);
    }
}
