<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Result>
 */
class ResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team'=> fake()->word(),
            'date'=>fake()->dateTimeBetween($startDate = '-5 years', $endDate = 'now', $timezone = null),
            'completion_time'=> fake()->time('H:i')
        ];
    }
}
