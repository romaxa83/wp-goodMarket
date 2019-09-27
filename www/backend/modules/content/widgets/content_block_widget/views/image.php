<?php

use backend\modules\filemanager\widgets\FileInput;

/** @var int $block_id */
/** @var string $value */
/** @var string $group */
?>

<?= FileInput::widget([
    'name' => "{$group}[{$block_id}][text]",
    'value' => $value,
    'buttonTag' => 'button',
    'buttonName' => 'Browse',
    'buttonOptions' => ['class' => 'btn btn-default'],
    'options' => ['class' => 'form-control', 'readonly' => 'readonly'],
    'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
    'thumb' => 'original',
    'imageContainer' => '.img',
    'pasteData' => FileInput::DATA_ID,
]); ?>