<?php

use backend\modules\filemanager\widgets\FileInput;
use unclead\multipleinput\MultipleInput;
use vova07\imperavi\Widget;
use yii\helpers\Html;

/** @var int $block_id */
/** @var string $value */
/** @var string $group */
?>

<?= MultipleInput::widget([
    'name' => "block[{$block_id}][text]",
    'allowEmptyList'    => false,
    'enableGuessTitle'  => true,
    'columns' => [
        [
            'name' => 'photo',
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
            'name' => 'title',
            'type' => 'textInput',
            'title' => 'Название',
            'columnOptions' => [
                'style' => 'width: 25%'
            ]
        ],
        [
            'name' => 'description',
            'type' => Widget::className(),
            'title' => 'Описание',
            'options' => [
                'settings' => [
                    'lang' => 'ru',
                    'minHeight' => 200,
                    'plugins' => [
                        'clips',
                        'fullscreen',
                    ],
                    'clips' => [
                        ['Lorem ipsum...', 'Lorem...'],
                    ],
                ],
            ]
        ]
    ],
    'data' => is_array($value) ? $value : unserialize($value)
]) ?>