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
            'max_time_expert' => $this->faker->numberBetween(1, 100),
            'max_time_jigsaw' => $this->faker->numberBetween(1, 100),
            'status' => $this->faker->randomElement(['onModule', 'onOption', 'onVerify'])
        ];
    }
}
