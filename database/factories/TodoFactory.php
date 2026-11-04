<?php

namespace Database\Factories;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->optional(0.7)->paragraph(),
            'category' => fake()->randomElement(['Work', 'Personal', 'Urgent']),
            'due_date' => fake()->optional(0.7)->dateTimeBetween('now', '+1 month'),
            'start_time' => null,
            'end_time' => null,
            'reminder_at' => null,
            'priority' => fake()->randomElement(['High', 'Medium', 'Low']),
            'completed' => false,
            'archived_at' => null,
        ];
    }

    /**
     * Indicate that the todo has been completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'completed' => true,
        ]);
    }

    /**
     * Indicate that the todo has been archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'archived_at' => now(),
        ]);
    }
}
