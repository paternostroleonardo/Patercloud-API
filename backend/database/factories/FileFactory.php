<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::all();
        return [
            'uuid' => Str::uuid(),
            'name' => $this->faker->name(),
            'path' => $this->faker->url(),
            'size' => $this->faker->randomNumber(5, false),
            'author_id' => $user->random()->id
        ];
    }
}
