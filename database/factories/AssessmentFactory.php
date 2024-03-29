<?php

namespace Database\Factories;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assessment>
 */
class AssessmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'topic_id' => Topic::find(rand(1, Topic::all()->count()))->id,
            'is_publish' => $this->faker->boolean,
            'is_complete' => $this->faker->boolean,
            'is_ready_publish' => 0,
        ];
    }
}
