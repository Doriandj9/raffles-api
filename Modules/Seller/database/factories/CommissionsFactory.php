<?php

namespace Modules\Seller\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommissionsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Seller\app\Models\Commissions::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

