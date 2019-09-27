<?php 

return [
    'create' => [
        'PostLang' => [
            'ru' => [
                'title' => 'ruPost',
                'content' => '<p>ruContent</p>',
                'description' => '<p>ruDescription</p>'
            ],
            'en' => [
                'title' => 'enPost',
                'content' => '<p>enContent</p>',
                'description' => '<p>enDescription</p>'
            ]
        ],
        'PostForm' => [
            'category_id' => 2,
            'alias' => 'test',
            'status' => 0,
            'published_at' => '09-09-2019 10:40',
            'media_id' => 1
        ],
        'TagsForm' => [
            'existing' => [
                0 => 'ruTag'
            ]
        ],
        'MetaForm' => [
            'h1' => 'testH1',
            'title' => 'testTitle',
            'keywords' => 'testKeywords',
            'description' => 'testDescription',
            'seo_text' => 'testSeoText'
        ]
    ],
    'update' => [
        'PostLang' => [
            'ru' => [
                'title' => 'ruPostUpdate',
                'content' => '<p>ruContentUpdate</p>',
                'description' => '<p>ruDescriptionUpdate</p>'
            ],
            'en' => [
                'title' => 'enPostUpdate',
                'content' => '<p>enContentUpdate</p>',
                'description' => '<p>enDescriptionUpdate</p>'
            ]
        ],
        'PostForm' => [
            'category_id' => 2,
            'alias' => 'post-update',
            'status' => 0,
            'published_at' => '09-09-2019 10:40',
            'media_id' => 1
        ],
        'TagsForm' => [
            'existing' => [
                0 => 'ruTag'
            ]
        ],
        'MetaForm' => [
            'h1' => 'testH1Update',
            'title' => 'testTitleUpdate',
            'keywords' => 'testKeywordsUpdate',
            'description' => 'testDescriptionUpdate',
            'seo_text' => 'testSeoTextUpdate'
        ]
    ]
];