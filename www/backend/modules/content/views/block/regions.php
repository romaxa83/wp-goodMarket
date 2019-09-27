<?php

/** @var int $id */
/** @var string $group */

use backend\modules\referenceBooks\models\Region;
use kartik\select2\Select2;
?>

<?php
$regions = Region::find()
    ->indexBy('id')
    ->asArray()
    ->all()
?>

<?= Select2::widget([
    'name' => "{$group}[{$id}][text]",
    'data' => array_map(function($region) {
        return $region['name'];
    }, $regions),
    'options' => [
        'placeholder' => 'Выбор рнгиона',
        'class' => 'form-control',
        'id' => "{$group}-{$id}",
    ]
]) ?>