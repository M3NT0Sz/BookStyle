<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            DB::table('books')->insert([
                'name' => $faker->name,
                'author' => $faker->name,
                'genre' => $faker->word,
                'condition' => $faker->randomElement(['new', 'used']),
                'price' => $faker->randomFloat(2, 5, 100),
                'description' => $faker->sentence,
                'images' => '["img.books\/CpZJhHss5BPx8boQUKBNgwRhllcrqN453LHRsC4P.jpg"]',
                'user_id' => 1,
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime
            ]);
        }
    }
}
