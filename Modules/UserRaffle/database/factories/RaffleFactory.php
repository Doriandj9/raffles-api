<?php

namespace Modules\UserRaffle\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RaffleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\UserRaffle\app\Models\Raffle::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

