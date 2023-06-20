<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DietFood>
 */
class DietFoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "food_id"       => $this->faker->numberBetween(1,22500)
            ,"d_id"         => $this->faker->numberBetween(1,368)
            ,"df_intake"    => $this->faker->numberBetween(1,10)
            ,"created_at"   => $this->faker->dateTimeBetween("-3months")
        ];
    }
}
