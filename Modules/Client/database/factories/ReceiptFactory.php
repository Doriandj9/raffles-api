<?php

namespace Modules\Client\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReceiptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Client\app\Models\Receipt::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

