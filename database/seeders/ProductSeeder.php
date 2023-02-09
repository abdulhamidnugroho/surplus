<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'name' => Str::random(10),
                'description' => Str::random(20) . Str::random(20),
                'enable' => TRUE,
            ],
            [
                'name' => Str::random(10),
                'description' => Str::random(20) . Str::random(20),
                'enable' => FALSE,
            ],
            [
                'name' => Str::random(10),
                'description' => Str::random(20) . Str::random(20),
                'enable' => TRUE,
            ]
        ]);
    }
}
