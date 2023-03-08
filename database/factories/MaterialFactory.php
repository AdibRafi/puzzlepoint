<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Material>
 */
class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->text(20),
            'file_path' => $this->faker->filePath(),
            'classroom_id' => Classroom::find(rand(1,Classroom::all()->count()))->id,
        ];
    }
}
