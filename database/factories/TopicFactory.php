<?php

namespace Database\Factories;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Topic>
 */
class TopicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'classroom_id' => Classroom::find(rand(1, Classroom::all()->count()))->id,
            'name' => $this->faker->text(10),
            'date_time' => $this->faker->dateTime,
            'no_of_modules' => $this->faker->numberBetween(2, 6),
            'max_session' => $this->faker->numberBetween(20, 120),
            'max_buffer' => $this->faker->numberBetween(1, 30),
            'max_time_expert' => $this->faker->numberBetween(1, 100),
            'max_time_jigsaw' => $this->faker->numberBetween(1, 100),
            'transition_time' => $this->faker->numberBetween(2, 5),
            'status' => $this->faker->randomElement(['onModule', 'onOption', 'onVerify']),
            'is_expert_form' => $this->faker->boolean,
            'is_jigsaw_form' => $this->faker->boolean,
            'is_new' => $this->faker->boolean,
            'is_start' => $this->faker->boolean,
            'is_complete' => $this->faker->boolean,
        ];
    }
}
