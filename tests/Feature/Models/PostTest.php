<?php

namespace Tests\Feature\Models;

use App\Models\Post;
use App\Models\User;
use Database\Factories\PostFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    protected $user;
    protected $post;
    protected $posts;

    protected function setUp() : void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->posts = Post::factory()->count(20)->for($this->user)->create();
        $this->post = $this->posts->first();

        $this->assertModelExists($this->user);
    }

  
    public function test_a_user_can_read_all_posts()
    {
        $this->assertDatabaseHas('users', [
            'email' => $this->user->email,
        ]);
        $this->assertDatabaseCount('posts', 20);
        $response = $this->get('/');
        $response->assertStatus(200);

    }

    public function test_post_create_screen_can_be_rendered()
    {
        $response = $this->actingAs($this->user)->get(route('dashboard.posts.create'));
        $response->assertStatus(200);
    }

    public function test_a_user_can_read_single_post()
    {
        $this->assertDatabaseHas('users', [
            'email' => $this->user->email,
        ]);
        $this->be($this->user);
        $post = Post::factory()->for($this->user)->create();
        $response = $this->get('/'. $post->slug);
        $response->assertSee($post->title);
    }


    public function test_post_request_validation()
    {
        
        $post = $this->post->toArray();
        unset($post['title']);
        $response = $this->actingAs($this->user)->followingRedirects()->post(route('dashboard.posts.store'),$post);
        
        $response->assertStatus(200);
    }


    public function test_only_authenticated_user_can_add_a_post()
    {
        $post = $this->post->toArray();
        $post['title'] = $post['title'] . rand(2323,43434);

        $response = $this->actingAs($this->user)->followingRedirects()->post(route('dashboard.posts.store'),$post);
        $response->assertSuccessful();
        $response->assertStatus(200);
        $this->assertEquals(21,Post::all()->count());
    }

    public function test_authenticated_should_not_be_able_to_edit_a_post()
    {
        $response = $this->actingAs($this->user)->put('/dashboard/'. $this->post->slug. '/update', $this->post->toArray());
        $response->assertStatus(404);
    }

    public function test_authenticated_should_not_be_able_to_delete_a_post()
    {
        $response = $this->delete('/dashboard/'. $this->post->slug. '/delete');
        $response->assertStatus(404);
    }
}
