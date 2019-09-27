<?php

use yii\helpers\Html;

/** @var int $id */
/** @var string $group */
/** @var string $template */
?>

<div class="new-block">
    <div class="form-group">
        <?= Html::label('Название блока', '', ['class' => 'control-label']) ?>
        <?= Html::textInput("{$group}[{$id}][name]", '', ['class' => 'form-control']) ?>
    </div>
    <div class="form-group">
        <?= Html::label('Подпись', '', ['class' => 'control-label']) ?>
        <?= Html::textInput("{$group}[{$id}][label]", '', ['class' => 'form-control']) ?>
    </div>
    <?= Html::input('hidden', "{$group}[{$id}][type]", $template) ?>

    <?= $this->render($template, compact('id', 'group')) ?>
</div>