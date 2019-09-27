<?php
use yii\helpers\Html;

/** @var int $id */
/** @var string $group */
?>

<div class="form-group">
    <?= Html::label('Контент', '', ['class' => 'control-label']) ?>
    <?= Html::textarea(
        "{$group}[{$id}][text]",
        '',
        [
            'class' => 'form-control',
            'id' => "{$group}-{$id}",
        ]
    ) ?>
</div>