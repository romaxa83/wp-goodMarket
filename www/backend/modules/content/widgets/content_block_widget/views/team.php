<?php

use backend\modules\filemanager\widgets\FileInput;
use unclead\multipleinput\MultipleInput;

/** @var int $block_id */
/** @var string $value */
/** @var string $group */
?>

<?= MultipleInput::widget([
    'name' => "{$group}[{$block_id}][text]",
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
            ]
        ],
        [
            'name' => 'name',
            'type' => 'textInput',
            'title' => 'Имя'
        ],
        [
            'name' => 'description',
            'type' => 'textInput',
            'title' => 'Описание'
        ]
    ],
    'data' => is_array($value) ? $value : unserialize($value)
]) ?>