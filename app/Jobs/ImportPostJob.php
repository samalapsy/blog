<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImportPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
     
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
    
            $response= Http::acceptJson()->get(Config::get('blog.remote_blog_post_import_url'));
    
            if (!$response->successful()){
                $response->throw();
            }
    
            if($response->ok() && $response->successful()){
                $results = $response->json();
                if($results && count($results['data']) > 0){
                    $chucked_results = array_chunk(array_map(function($item){
                        return array_merge( $item,[
                            'user_id' => 1,
                            'slug' => Str::slug($item['title']),
                            'created_at' => now(),
                            'updated_at' => now(),
                            'publication_date' => Carbon::create($item['publication_date'])->toDateTimeString(), // Defensive programming
    
                        ]);
                    }, $results['data']), 1000);
    
                    foreach($chucked_results as $posts){
                        Post::insertOrIgnore($posts);
                        Log::info('[ImportPostJob:handle] == Chunk inserted '. count($posts) . ' posts');
                    }
    
                    return 1;
                }else{
                    Log::critical('[ImportPostJob:handle] == No Posts Returned from endpoint', $response);
                }
            }else{
                Log::critical('[ImportPostJob:handle] == ', $response); // alert system should pick this up
            }
        } catch (\Exception $e) {
            dd($e);
            Log::critical('[ImportPostJob:handle] Exception == ', $e); // alert system should pick this up
        }
    }
}
