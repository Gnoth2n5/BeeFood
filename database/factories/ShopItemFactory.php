<?php

namespace Database\Factories;

use App\Models\ShopItem;
use App\Models\UserShop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShopItem>
 */
class ShopItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_shop_id' => UserShop::factory(),
            'name' => fake()->words(rand(2, 4), true),
            'description' => fake()->paragraph(rand(1, 3)),
            'price' => fake()->randomFloat(2, 5, 500),
            'featured_image' => fake()->imageUrl(400, 300, 'products'),
            'is_active' => fake()->boolean(80),
            'stock_quantity' => fake()->numberBetween(0, 100),
            'sku' => 'SKU-' . strtoupper(fake()->bothify('??##??')),
        ];
    }

    /**
     * Indicate that the shop item is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the shop item is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the shop item is in stock.
     */
    public function inStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_quantity' => fake()->numberBetween(1, 100),
        ]);
    }

    /**
     * Indicate that the shop item is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_quantity' => 0,
        ]);
    }
}
