<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->title(),
            'description' => fake()->text(60),
            'number_raffles' => fake()->randomDigit(),
            'price' => fake()->randomFloat(2,1,12),
            'minimum_tickets' => 1,
            'maximum_tickets' => fake()->numberBetween(50,300),
        ];
    }
}
