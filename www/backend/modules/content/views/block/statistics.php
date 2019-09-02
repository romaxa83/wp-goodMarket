<?php

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
            'name' => 'value',
            'type' => 'textInput',
            'title' => 'Значение'
        ],
        [
            'name' => 'description',
            'type' => 'textInput',
            'title' => 'Описание'
        ]
    ],
]) ?>