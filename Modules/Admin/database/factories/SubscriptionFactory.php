<?php

namespace Modules\Admin\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Admin\app\Models\Subscription::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

