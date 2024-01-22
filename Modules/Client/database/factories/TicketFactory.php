<?php

namespace Modules\Client\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Client\app\Models\Ticket::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

