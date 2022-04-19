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
        unset($this->user, $this->create_route, $this->store_route);
        \Mockery::close();
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
        $response = $this->actingAs($this->user)
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

        // ImportPostJob::dispatch();
        // $mock = $this->mock(ImportPostJob::class, function (MockInterface $mock) {
        //     $mock->shouldReceive('process')->once();
        // });

        Http::fake([
            Config::get('blog.remote_blog_post_import_url') => Http::response(
                [
                    'data' => [
                        [
                            'title' => "Est omnis beatae aut officiis.",
                            'description' => "Delectus et voluptatum minus harum perferendis unde optio commodi. Quia eaque modi impedit qui praesentium in omnis ab voluptas. Nihil officiis eveniet aspernatur labore et. Voluptate officiis corporis reiciendis corporis ullam non. Non asperiores recusandae veritatis. Culpa iure corrupti sit amet omnis.",
                            'publication_date' => "2022-04-18 16:38:47"
                        ],
                        [
                            'title' => "Neque asperiores beatae quam fugiat voluptatibus dolorem.",
                            'description' => "Ut maxime velit repellat inventore fugiat inventore. Molestiae suscipit dignissimos eum accusantium illo. Quas voluptate quae assumenda aut sit et.",
                            'publication_date' => "2022-04-18 21:38:28"
                        ],
                        [
                            'title' => "Nam molestias sit blanditiis eum.",
                            'description' => "Temporibus ipsam et odio cumque vel nam. Cupiditate eum quo laborum dignissimos alias minima explicabo. Dolor quo incidunt est odit dolor eos sit tenetur.",
                            'publication_date' => "2022-04-18 15:14:46"
                        ]
                    ]
                ], 200, ['Headers'])
        ]);
        
        Http::asJson();
    }


}
