Рекомендации к модели:
Объявляем публичное свойство public $languageData;

Использование виджета langwidget в Controller
1) LangWidget::getActiveLanguageData(['alias']) - Получение алиасов всех активных языков. Результат ['ru', 'en', 'ua']
2) LangWidget::validate($model) - Валидация полей по текущей модели. Результат true/false
3) $model->languageData = Category::find()->select(['alias', 'rating', 'language'])->asArray()->where(['stock_id' => $id])->all(); - заполняем languageData значением из базы данных

Использование виджета langwidget в View
1) use backend\widgets\langwidget\LangWidget;
2) echo LangWidget::widget(['model' => $model, 'fields' => [
    ['type' => 'text', 'name' => 'alias'],
    ['type' => 'number', 'name' => 'rating'],
    ['type' => 'widget', 'name' => 'rating', 'class' => 'vova07\imperavi\Widget', 'options' => [
            'settings' => [
                'lang' => 'ru',
                'minHeight' => 200,
                'plugins' => [
                    'clips',
                    'fullscreen',
                ],
            ]
        ]
   ]
]]);

!Примечание: по умолчанию виджет использует Русский язык (опционально задается в конфигах проекта).