<?php

namespace Tests\Feature\Models;

use App\Models\Post;
use Database\Factories\PostFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


    public function test_a_user_can_read_all_posts()
    {
        $posts =Post::factory()->make(20);

        $response = $this->get('/');
        $response->assertSee($posts->latest()->title);

    }



    public function test_a_user_can_read_single_post()
    {
        $post = Post::factory()->make();
        
        $response = $this->get('/'. $post->slug);
        // $response->assertSee($post->title);
        $this->assertDatabaseHas(
            'posts', 
            $post->toArray()
        );
    }


    public function test_only_authenticated_user_can_add_a_post()
    {
        $post = Post::factory()->make();

        $response = $this->postJson(
            route('dashboard.store-post'),
            $post->toArray()
        );

        $response->assertCreated();
        // $response->assertSee($post->title);
        $response->assertJson([
            'data' => ['slug' => $post->slug]
        ]);

        $this->assertDatabaseHas(
            'posts', 
            $post->toArray()
        );
    }

    public function test_authenticated_should_not_be_able_to_edit_a_post()
    {
        $post = Post::factory()->make(1);

        $response = $this->get('/'. $post->slug);

        // $response->assertSee($posts->title);
    }

    public function test_authenticated_should_not_be_able_to_delete_a_post()
    {
        $post = Post::factory()->create(1);
        // $post =  factory(App\Post::class)->create();
        $response = $this->delete('/'. $post->slug);

        // $response->assertSee($posts->title);
    }
}
