<?php

return [
    'params' => [
        'templates' => [
            'main' => [
                'route' => '/',
                'label' => 'Главная'
            ],
            'blog' => [
                'route' => 'site/view',
                'label' => 'Техническая страница'
            ],
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
            'categories' => 'Блок категории',
            'filter' => 'Фильтр'
        ],
    ]
];
