<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

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

            $published_posts = Post::pendingScheduledPosts()->where('publication_date', '<', now())->update([
                'is_published' => true,
                'published_at' => now(),
            ]);

            if(!$published_posts){
                $message = 'No Scheduled Post found as at '. now();
            }else{
                Cache::forget(Config::get('blog.caching.public.blog-listing'));
                Cache::forget(Config::get('blog.caching.dashboard.listing'));
                $message = '[ScheduledPostCommand:handle]: Found and processed ' . $published_posts . ' posts. 😉';
            }

            Log::info($message);
            $this->comment($message );
        } catch (ModelNotFoundException $e) {
            Log::critical('[ScheduledPostCommand:handle] == Not Found Exception' . $e);
            return -1;
        } catch (QueryException $e) { 
            Log::critical('[ScheduledPostCommand:handle] == DB Exception ' . $e);
            return -1;
        } catch (\Exception $e) {
            Log::critical('[ScheduledPostCommand:handle] == ' . $e);
            return -1;
        }
        
        return 0;

    }
}
