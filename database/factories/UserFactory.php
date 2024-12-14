<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'role' => fake()->randomElement(['user', 'admin']), // Adjust roles as needed
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'business_name' => fake()->optional()->company(),
            'dob' => fake()->optional()->date(),
            'gender' => fake()->randomElement(['male', 'female', 'other']),
            'address' => fake()->optional()->address(),
            'contact_number' => fake()->optional()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Default password
            'image' => fake()->optional()->imageUrl(200, 200, 'people'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
