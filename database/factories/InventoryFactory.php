<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => '1',
            'type_id' => '1',
            'name' => $this->faker->word(10,100),
            'price' => $this->faker->randomFloat(2, 1, 1000), 
            'desc' => $this->faker->sentence,
            'stock' => $this->faker->numberBetween(0, 999), 
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
