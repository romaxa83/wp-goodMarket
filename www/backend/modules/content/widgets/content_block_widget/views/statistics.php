<?php

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
    'data' => is_array($value) ? $value : unserialize($value)
]) ?>