<?php

namespace Database\Factories;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $site = $this->faker->url;
        $points = $this->faker->numberBetween(1, 1000);
        $username = $this->faker->userName;
        $createdAt = Carbon::parse($this->faker->dateTimeBetween('-2 years'))->diffForHumans();
        $comments = $this->faker->numberBetween(1, 300);

        return [
            'site' => $site,
            'title' => $this->faker->sentence . ' ' . $site,
            'score' => $points,
            'author' => $username,
            'created' => $createdAt,
            'comments' => $comments,
        ];
    }
}
