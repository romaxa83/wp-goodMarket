<?php

return [
    'params' => [
        'templates' => [
            'main' => [
                'route' => '/',
                'label' => 'Главная'
            ],
            'blog' => [
                'route' => 'blog/index',
                'label' => 'Блог'
            ],
            'blog-record' => [
                'route' => 'blog/record',
                'label' => 'Запись блога'
            ]
        ],
        'blockTypes' => [
            'editor' => 'Редактор',
            'string' => 'Строка',
            'textarea' => 'Текстовая область',
            'team' => 'Команда',
            'cards' => 'Карточки',
            'statistics' => 'Статистика',
            'banners' => 'Баннеры',
            'image' => 'Изображение',
            'api-country-selector' => 'Страна из API',
            'resorts' => 'Курорты',
            'regions' => 'Регионы',
            'categories' => 'Категории',
            'filter' => 'Фильтр'
        ],
    ]
];
