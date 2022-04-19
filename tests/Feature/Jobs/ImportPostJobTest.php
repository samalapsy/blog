<?php

namespace Tests\Feature\Jobs;

use App\Jobs\ImportPostJob;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;
use Mockery;
use Mockery\Mock;
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
    protected $store_route= 'dashboard.import-posts.store';
    protected $create_route= 'dashboard.import-posts.create';
    
    protected function setUp() : void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->assertModelExists($this->user);
    }

    protected function tearDown(): void {
        parent::tearDown();
        Mockery::close();
        unset($this->user, $this->create_route, $this->store_route);
    }

    public function test_import_post_screen_can_be_rendered()
    {
        $response = $this->actingAs($this->user)->get(route($this->create_route));
        $response->assertStatus(200);
    }


    public function test_click_import_post_button_validate()
    {
        
        $this->actingAs($this->user)->from(route($this->create_route))
            ->post(route($this->store_route),[])
            ->assertStatus(302)
            ->assertRedirect(route($this->create_route))
            ->assertSessionHasErrors(['import_post' => 'The import post field is required.']);
    }

    public function test_import_post_button_is_sent_to_dispatch_job()
    {
        $response =$this->actingAs($this->user)->from(route($this->create_route))
            ->post(route($this->store_route), [ 
                'import_post' => 'continue'
            ]);

        Queue::fake();
        ImportPostJob::dispatch();
        Queue::assertPushed(ImportPostJob::class);
        $response->assertStatus(302)
            ->assertRedirect(route($this->create_route))
            ->assertSessionDoesntHaveErrors();
    }

    public function test_job_was_not_pushed()
    {
        $this->actingAs($this->user)
            ->from(route($this->create_route))
            ->post(route('dashboard.import-posts.store'), [ 
                'import_post' => 'continue'
            ])->assertStatus(302)
            ->assertRedirect(route($this->create_route));
        Queue::fake();
        Queue::assertNothingPushed(ImportPostJob::class);
    }



    public function test_import_from_endpoint()
    {

        Http::fake();
        /* $mock_response = $this->mock(ImportPostJob::class, function (MockInterface $mock) {
            $mock->shouldReceive('dispatch')->once()->andReturn(1);
        });
        ImportPostJob::dispatch(); */
        Http::asJson();
        
    }


}
