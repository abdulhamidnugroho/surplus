<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('images')->insert([
            [
                'name' => Str::random(10),
                'file' => 'images/product_images/surplus.png',
                'enable' => TRUE,
            ],
            [
                'name' => Str::random(10),
                'file' => 'images/product_images/surplus.png',
                'enable' => TRUE,
            ],
            [
                'name' => Str::random(10),
                'file' => 'images/product_images/surplus.png',
                'enable' => TRUE,
            ],
        ]);
    }
}
