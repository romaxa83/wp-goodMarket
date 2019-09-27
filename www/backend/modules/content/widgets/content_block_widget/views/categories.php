<?php


use backend\modules\content\models\ChannelCategory;
use kartik\select2\Select2;

/** @var int $block_id */
/** @var string $value */
/** @var string $group */
?>

<?php
$categories = ChannelCategory::find()
    ->where(['status' => 1])
    ->indexBy('id')
    ->asArray()
    ->all();
?>

<?= Select2::widget([
    'name' => "{$group}[{$block_id}][text]",
    'data' => array_map(function($category) {
        return $category['title'];
    }, $categories),
    'value' => $value,
    'options' => [
        'placeholder' => 'Выбор категории',
        'class' => 'form-control',
    ]
]) ?>