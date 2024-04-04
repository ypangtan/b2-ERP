<?php

namespace Database\Factories;

use App\Models\{
    Customer,
    Inventory,
    Lead,
    Supplier,
};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        return [
            'supplier_id' => Supplier::factory()->create()->id,
            'inventory_id' => Inventory::factory()->create()->id,
            'quantity' => $this->faker->numberBetween(1, 100),
            'remark' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 1, 100),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
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
