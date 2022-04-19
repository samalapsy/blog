<?php

namespace Tests\Feature\Jobs;

use App\Jobs\ImportPostJob;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Mockery;
use Mockery\MockInterface;

class ImportPostJobTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    protected $user;
    protected function setUp() : void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->assertModelExists($this->user);
        // $this->withoutExceptionHandling(); 
    }

    public function test_import_post_screen_can_be_rendered()
    {
        $response = $this->actingAs($this->user)->get(route('dashboard.import-posts.create'));
        $response->assertStatus(200);
    }


    public function test_click_import_post_button_validate_and_redirect()
    {
        $this->actingAs($this->user);
        $response = $this->followingRedirects()->post(route('dashboard.import-posts.store'),[]);
        $response->assertStatus(200);
    }

    public function test_import_post_button_is_sent_to_dispatch_job()
    {
        // $response = $this->be($this->user)
        // 
        $this->actingAs($this->user);
        $response =$this->followingRedirects()->post(route('dashboard.import-posts.store'));
        Queue::fake();
        ImportPostJob::dispatch();
        Queue::assertPushed(ImportPostJob::class);
        $response->assertStatus(200);
    }

    public function test_job_was_not_pushed()
    {
        $response = $this->be($this->user)->followingRedirects()->post(route('dashboard.import-posts.store'));
        Queue::fake();
        Queue::assertNothingPushed(ImportPostJob::class);
        $response->assertStatus(200);
        // $response->assertSee('Your posts have been scheduled to be imported. 😉');
    }



    public function test_import_from_endpoint()
    {

        ImportPostJob::dispatch();
        $mock = $this->mock(ImportPostJob::class, function (MockInterface $mock) {
            $mock->shouldReceive('process')->once();
        });

        Http::fake();
        $response  = Http::get(Config::get('blog.remote_blog_post_import_url'), );
        Http::asJson();
    }


}
