<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'author' => fake()->name,
            'genre' => fake()->word,
            'condition' => fake()->randomElement(['new', 'used']),
            'price' => fake()->randomFloat(2, 5, 100),
            'description' => fake()->sentence,
            'images' => 'https://lh6.googleusercontent.com/proxy/EwR7dYBwBBkGApuX1Sjuo89Z3mAThn3czvbnt1aS4AhSWxuG9JT5YAnr2y_tBzsMuK_HXuupUi7hAgjh',
            'user_id' => 1,
            'created_at' => fake()->dateTime,
            'updated_at' => fake()->dateTime
        ];
    }
}
