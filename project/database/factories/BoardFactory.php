<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Board>
 */
class BoardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id'       => $this->faker->unique()->randomNumber(2)
            ,'btitle'       => $this->faker->unique()->realText(15)
            ,'bcontent'     => $this->faker->realText(1000)
            ,'bcate_id'     => $this->faker->numberBetween(1, 5)
            ,'created_at'   => $this->faker->dateTimeBetween('-1 years')
        ];
    }
}
