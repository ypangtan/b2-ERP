<?php

namespace Database\Factories;

use App\Models\{
    Lead,
};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $lead = Lead::factory()->create();

        return [
            'customer_id' => $lead->customer_id,
            'inventory_id' => $lead->inventory_id,
            'lead_id' => $lead->id,
            'comment' => $this->faker->sentence,
            'rating' => $this->faker->randomElement(['1', '2', '3', '4', '5']),
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
