<?php

namespace Tests\Feature\Console\Commands;

use App\Models\Post;
use Carbon\Carbon;
use Database\Factories\PostFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ScheduledPostCommandTest extends TestCase
{
    use RefreshDatabase;
    private $command= 'blog:publish-posts';
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_update_all_scheduled_posts()
    {
        Post::factory(20)->create([
            'publication_date' => Carbon::yesterday(),
        ]);
        
        $this->artisan($this->command)->assertSuccessful();
    }

    public function test_cannot_update_scheduled_posts_greater_than_now()
    {
        Post::factory(20)->create([
            'publication_date' => Carbon::now()->addMinutes(20),
        ]);

        $this->artisan($this->command)->assertExitCode(-1);
        $this->artisan($this->command)->assertFailed();
    }

}
