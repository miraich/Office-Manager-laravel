<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->realText(),
            'budget' => $this->faker->numberBetween(1000, 999999),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'documentation' => $this->faker->realText(),
        ];
    }
}
