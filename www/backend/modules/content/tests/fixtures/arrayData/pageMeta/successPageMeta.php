<?php

return [
    'create' => [
        'PageLang' => [
            'ru' => [
                'title' => 'testCreateR'
            ],
            'en' => [
                'title' => 'testCreateU'
            ]
        ],
        'PageMetaLang' => [
            'ru' => [
                'title' => 'testCreateR',
                'description' => 'testCreateR',
                'keywords' => 'testCreateR'
            ],
            'en' => [
                'title' => 'testCreateU',
                'description' => 'testCreateU',
                'keywords' => 'testCreateU'
            ]
        ],
        'Page' => [
            'status' => 1
        ],
        'slug' => [
            'slug' => 'testCreateR',
            'id' => '',
            'route' => '/',
            'template' => 'main'
        ],
        'slag-action' => '/admin/content/page/get-route-for-template',
        'content-parent' => 'page-new-content',
        'content-group' => 'block',
        'block-type' => 'editor'
    ],
    'update' => [
        'PageLang' => [
            'ru' => [
                'title' => 'testUpdateR'
            ],
            'en' => [
                'title' => 'testUpdateU'
            ]
        ],
        'PageMetaLang' => [
            'ru' => [
                'title' => 'testUpdateR',
                'description' => 'testUpdateR',
                'keywords' => 'testUpdateR'
            ],
            'en' => [
                'title' => 'testUpdateU',
                'description' => 'testUpdateU',
                'keywords' => 'testUpdateU'
            ]
        ],
        'Page' => [
            'status' => 1
        ],
        'slug' => [
            'slug' => 'testUpdateR',
            'id' => '',
            'route' => '/',
            'template' => 'main'
        ],
        'slag-action' => '/admin/content/page/get-route-for-template',
        'content-parent' => 'page-new-content',
        'content-group' => 'block',
        'block-type' => 'editor'
    ]
];