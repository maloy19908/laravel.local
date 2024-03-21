<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name ,
            'phone_personal' => $this->faker->phoneNumber(),
            'phone_personal_other' => $this->faker->phoneNumber(),
            'avito_url' => $this->faker->url(),
            'site' => Str::random(7) .'.com',
            'email' => Str::random(10) . '@gmail.com',
            'comment' => $this->faker->text(50),
            'phone_avito' => $this->faker->phoneNumber(),
            'phone_yula' => $this->faker->phoneNumber(),
            'cars' => $this->faker->text(50),
            'delivery_cost' => random_int(1000,7000),
            'cars_other' => $this->faker->text(50),
            'order_pay' => $this->faker->text(10),
            'adress' => $this->faker->address(),
            'min_cost' => '2м3',
            'min_cost_unique' => '5м3',
            'comment_other' => $this->faker->text(10),
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
