<?php

use backend\modules\referenceBooks\models\Country;
use kartik\select2\Select2;

/** @var int $block_id */
/** @var string $value */
/** @var string $group */
?>

<?php
$countries = Country::find()
    ->where(['status' => 1])
    ->indexBy('id')
    ->asArray()
    ->all();
?>

<?= Select2::widget([
    'name' => "{$group}[{$block_id}][text]",
    'data' => array_map(function($country) {
        return $country['name'];
    }, $countries),
    'value' => $value,
    'options' => [
        'placeholder' => 'Выбор Страны из API',
        'class' => 'form-control',
    ]
]) ?>