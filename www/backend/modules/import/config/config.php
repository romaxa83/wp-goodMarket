<?php

return [
    'params' => [
        'import_settings' => [
            'limit_import' => 50,
            'def_fields_value' => [
                'category_id' => 0,
                'product_name' => 'Название товара',
                'price' => 0,
                'description' => 'Описание',
                'manufacturer' => 'Нету поставщика',
                'available' => 'false'
            ],
            'ssl' => false
        ],
        'currency' => [
            'UAH',
            'USD',
            'EUR',
            'RUB'
        ],
        'update_frequency' => [
            'час',
            'день',
            'неделя',
            'месяц'
        ],
        'xml-main-fields' => [
            'id' => 'Идентификатор продукта',
            'category_id' => 'Идентификатор категории',
            'vendor_code' => 'Артикул',
            'img' => 'Изображения',
            'product_name' => 'Название товара',
            'price' => 'Цена',
            'description' => 'Описание',
            'manufacturer' => 'Производитель',
            'available' => 'Наличие'
        ],
        'xml-additional-fields' => [
            'unit' => 'Единица измерения',
            'amount' => 'Остаток на складе',
            'price_old' => 'Старая цена',
            'attr' => 'Атрибуты',
        ]
    ]
];
