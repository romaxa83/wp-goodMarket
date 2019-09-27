<?php

use backend\modules\filemanager\widgets\FileInput;
use backend\modules\filter\models\Filter;
use unclead\multipleinput\MultipleInput;
use yii\helpers\ArrayHelper;

/** @var int $block_id */
/** @var string $value */
/** @var string $group */
/** @var array $filter */
?>

<?php $filter = ArrayHelper::map(Filter::find()->where(['status'=> TRUE])->asArray()->all(), 'alias', 'name'); ?>

<?= MultipleInput::widget([
    'id' => "{$group}-{$block_id}",
    'name' => "{$group}[{$block_id}][text]",
    'allowEmptyList'    => false,
    'enableGuessTitle'  => true,
    'min' => 3,
    'max' => 3,
    'columns' => [
        [
            'name' => 'name',
            'type' => 'textInput',
            'title' => 'Имя'
        ],
        [
            'name' => 'description',
            'type' => 'textInput',
            'title' => 'Описание'
        ],
        [
            'name' => 'filter',
            'type' => 'dropDownList',
            'title' => 'Тип фильтра',
            'items' => $filter,
            'options' => [
                'prompt' => 'Выбор фильтра',
                'class' => 'form-control',
            ]
        ],
        [
            'name' => 'image',
            'type' => FileInput::className(),
            'title' => 'Изображение',
            'options' => [
                'buttonTag' => 'button',
                'buttonName' => 'Browse',
                'buttonOptions' => ['class' => 'btn btn-default'],
                'options' => ['class' => 'form-control', 'readonly' => 'readonly'],
                'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                'thumb' => 'original',
                'imageContainer' => '.img',
                'pasteData' => FileInput::DATA_ID,
            ],
            'columnOptions' => [
                'style' => 'width: 14%'
            ]
        ],
        [
            'name' => 'bluer',
            'type' => FileInput::className(),
            'title' => 'Размытие',
            'options' => [
                'buttonTag' => 'button',
                'buttonName' => 'Browse',
                'buttonOptions' => ['class' => 'btn btn-default'],
                'options' => ['class' => 'form-control', 'readonly' => 'readonly'],
                'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                'thumb' => 'original',
                'imageContainer' => '.img',
                'pasteData' => FileInput::DATA_ID,
            ],
            'columnOptions' => [
                'style' => 'width: 14%'
            ]
        ],
    ],
    'data' => is_array($value) ? $value : unserialize($value)
]) ?>