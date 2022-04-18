<?php

return [
    'popular_post_view_count' => 100,
    'remote_blog_post_import_url'  => env('BLOG_IMPORT_URL'),
    'caching' => [
        'public' =>[
            'blog-listing' =>  60 * 60 * 3
        ],
        'dashboard' =>[
            'listing' =>  60 * 60 * 3
        ]
    ],
    'pagination_count' =>[
        'posts' => [
            'public' => 30,
            'dashboard' => 50,
        ]
    ],
];