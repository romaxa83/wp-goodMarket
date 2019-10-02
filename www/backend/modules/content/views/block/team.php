<?php

use backend\modules\filemanager\widgets\FileInput;
use unclead\multipleinput\MultipleInput;

/** @var int $id */
/** @var string $group */
?>

<?= MultipleInput::widget([
    'id' => "{$group}-{$id}",
    'name' => "{$group}[{$id}][text]",
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
]) ?>