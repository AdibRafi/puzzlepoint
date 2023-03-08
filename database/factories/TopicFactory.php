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
            'date' => $this->faker->date,
            'time' => $this->faker->randomFloat(2,0,100),
            'status' => $this->faker->randomElement(['NotStart', 'Done', 'Ongoing'])
        ];
    }
}
