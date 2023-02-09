<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_images')->insert([
            [
                'product_id' => 1,
                'image_id' => 1,
            ],
            [
                'product_id' => 2,
                'image_id' => 2,
            ],
            [
                'product_id' => 3,
                'image_id' => 3,
            ]
        ]);
    }
}
