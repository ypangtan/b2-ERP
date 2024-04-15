<?php

namespace Database\Factories;

use App\Models\{
    Customer,
    Inventory,
    Administrator
};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $status = $this->faker->randomElement(['10', '20', '30', '40']);

        if( $status == '20' || $status == '30' ){
            $customer = Customer::factory()->create();
            $customer->status = '20';
            $customer->save();
            return [
                'customer_id' => $customer->id,
                'inventory_id' => function () {
                    return Inventory::factory()->create()->id;
                },
                'user_id' => $this->faker->randomElement(['1', '2', '3', '4']),
                'status' => $status,
                'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'), 
                'updated_at' => now(),
            ];
        }else{
            return [
                'customer_id' => Customer::factory()->create()->id,
                'inventory_id' => Inventory::factory()->create()->id,
                'user_id' => $this->faker->randomElement(['1', '2', '3', '4']),
                'status' => $status,
                'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'), 
                'updated_at' => now(),
            ];
        }
       
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
