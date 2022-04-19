<?php

namespace App\Console\Commands;

use Exception;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ScheduledPostCommand extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'blog:publish-posts';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Find and publish all scheduled posts';

    /**
     * Execute the console command.
     * @return int
     */
    public function handle()
    {        
        try {

            $published_posts = Post::pendingScheduledPosts()->update([
                'is_published' => true,
                'published_at' => now(),
            ]);

            if(!$published_posts){
                $message = 'No Scheduled Post found as at '. now();
                return -1;
            }else{
                Post::forgetCachedPosts();
                $message = '[ScheduledPostCommand:handle]: Found and processed ' . $published_posts . ' posts. 😉';
            }

            Log::info($message);
            $this->comment($message );
            return 0;
        } catch (ModelNotFoundException | QueryException $e) {
            Log::critical('[ScheduledPostCommand:handle] == Not Found Exception' . json_encode($e));
            return -1;
        } catch (Exception $e) {
            Log::critical('[ScheduledPostCommand:handle] == General Exception '. json_encode($e));
            return -1;
        }

    }
}
