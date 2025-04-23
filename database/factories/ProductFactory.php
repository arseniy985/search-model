<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Placeholder image URL instead of relying on existing files
        $imageUrl = 'images/placeholder.jpg';
        
        // Get a random category ID
        $categoryIds = Category::pluck('id')->toArray();
        
        return [
            'title' => 'Product - ' . fake()->words(3, true),
            'image' => $imageUrl,
            'description' => fake()->realText(200),
            'price' => fake()->randomFloat(2, 50, 500),
            'published' => 1,
            'category_id' => $categoryIds ? Arr::random($categoryIds) : null,
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
