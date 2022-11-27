<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $dummy_date = $this->faker->dateTimeBetween('+1day', '+20day');
        return [
            'title' => $this->faker->sentence(rand(1,4)),
            'content' => $this->faker->realText(30),
            'start_at' => $dummy_date->format('Y-m-d'),
            'end_at' => $dummy_date->modify('+2day')->format('Y-m-d'),
        ];
    }
}
