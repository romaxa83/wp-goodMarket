<?php

use backend\modules\filemanager\widgets\FileInput;

/** @var int $id */
/** @var string $group */
?>

<?= FileInput::widget([
    'id' => "{$group}-{$id}",
    'name' => "{$group}[{$id}][text]",
    'buttonTag' => 'button',
    'buttonName' => 'Browse',
    'buttonOptions' => ['class' => 'btn btn-default'],
    'options' => ['class' => 'form-control', 'readonly' => 'readonly'],
    'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
    'thumb' => 'original',
    'imageContainer' => '.img',
    'pasteData' => FileInput::DATA_ID,
]); ?>
