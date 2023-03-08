<?php

namespace Database\Factories;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::find(rand(1, User::all()->count()))->id,
            'topic_id' => Topic::find(rand(1, Topic::all()->count()))->id,
            'date' => $this->faker->date,
            'attend_status' => $this->faker->randomElement(['attend', 'absent'])
        ];
    }
}
