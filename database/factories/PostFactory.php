<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = $this->faker->sentence();
        return [
            'user_id' => random_int(1, 1001),
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(25),
            'publication_date' => Carbon::now()->addMinute(rand(0,99)),
        ];
    }
}
