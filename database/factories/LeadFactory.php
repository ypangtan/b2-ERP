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
        $i = $this->faker->randomElement(['10', '20', '30', '40']);
        if( $i == '20' || $i == '30' ){

            $customer = Customer::factory()->create();
            $customer->status = '20';
            $customer->save();
            return [
                'customer_id' => $customer->id,
                'inventory_id' => function () {
                    return Inventory::factory()->create()->id;
                },
                'user_id' => '1',
                'status' => $i,
                'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'), 
                'updated_at' => now(),
            ];
        }else{
            return [
                'customer_id' => function () {
                    return Customer::factory()->create()->id;
                },
                'inventory_id' => function () {
                    return Inventory::factory()->create()->id;
                },
                'user_id' => '1',
                'status' => $i,
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
