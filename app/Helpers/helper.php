<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

Str::macro('postReadTime', function(...$text) {
    $totalWords = str_word_count(implode(" ", $text));
    $minutesToRead = round($totalWords / 200);
    return (int)max(1, $minutesToRead);
});


function truncate(string $string, int $limit = 100) : String
{
    return Str::limit($string,$limit);
}

function blogListingReadableTime($date) : String
{
    return $date->diffForhumans();
}


function getPostReadTime($string) : String
{
    return !is_null($string) ? Str::postReadTime($string). ' min(s) read' : null;
}


function getUserFirstname($string) : String
{   
    return !is_null($string) ? Str::of($string)->explode(' ')[0] : 'NA';
}

function forgetCache($key_name) : int
{
    $redis = Cache::getRedis();
    $keys = $redis->keys("*$key_name*");
    $count = 0;
    foreach ($keys as $key) {
        $redis->del($key);
        $count++;
    }      
    return $count;
}