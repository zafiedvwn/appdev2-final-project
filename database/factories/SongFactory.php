<?php

namespace Database\Factories;

use App\Models\Song;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Song>
 */
class SongFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Song::class;
    public function definition(): array
    {
        return [
            'song_title' => $this->faker->word,
            'album' => $this->faker->word,
            'artist' => $this->faker->name,
            'year' => $this->faker->year,
        ];
    }
}
