<?php

return [
    'popular_post_view_count' => 100,
    'remote_blog_post_import_url'  => env('BLOG_IMPORT_URL'),
    'caching' => [
        'public' =>[
            'blog-listing' => 60* 30
        ],
        'dashboard' =>[
            'listing' => 60* 30
        ]
    ],
    'pagination_count' =>[
        'posts' => [
            'public' => 30,
            'dashboard' => 50,
        ]
    ],
];