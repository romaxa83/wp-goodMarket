<?php

use backend\modules\referenceBooks\models\Region;
use kartik\select2\Select2;

/** @var int $block_id */
/** @var string $value */
/** @var string $group */
?>

<?php
$regions = Region::find()
    ->indexBy('id')
    ->asArray()
    ->all()
?>

<?= Select2::widget([
    'name' => "{$group}[{$block_id}][text]",
    'data' => array_map(function($region) {
        return $region['name'];
    }, $regions),
    'value' => $value,
    'options' => [
        'placeholder' => 'Выбор рнгиона',
        'class' => 'form-control',
    ]
]) ?>
