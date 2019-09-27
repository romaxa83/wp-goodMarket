<?php

return [
    'route1' => [
        'id'        => 1,
        'slug'      => 'main',
        'route'     => '/',
        'template'  => 'main',
        'parent_id' => 0,
        'post_id'   => 0
    ],
    'route2' => [
        'id'        => 2,
        'slug'      => 'blog',
        'route'     => 'blog/index',
        'template'  => 'blog',
        'parent_id' => 0,
        'post_id'   => 0
    ],
    'route3' => [
        'id'        => 3,
        'slug'      => 'post-1',
        'route'     => 'blog/record',
        'template'  => 'blog-record',
        'parent_id' => 2,
        'post_id'   => 1
    ]
];