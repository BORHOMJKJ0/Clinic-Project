<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'first_Name' => fake()->firstName,
            'last_Name' => fake()->lastName,
            'email' => fake()->unique()->safeEmail,
            'email_verified_at' => fake()->optional()->dateTime(),
            'password' => bcrypt('password'),
            'image' => $this->faker->imageUrl(200, 200),
            'phone' => fake()->unique()->phoneNumber,
            'role' => 'patient',
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
