<?php
/** @var int $id */
/** @var string $group */

use backend\modules\content\models\ChannelCategory;
use kartik\select2\Select2;

?>

<?php
$categories = ChannelCategory::find()
    ->where(['status' => 1])
    ->indexBy('id')
    ->asArray()
    ->all();
?>

<?= Select2::widget([
    'name' => "{$group}[{$id}][text]",
    'data' => array_map(function($category) {
        return $category['title'];
    }, $categories),
    'options' => [
        'placeholder' => 'Выбор категории',
        'class' => 'form-control',
        'id' => "{$group}-{$id}",
    ]
]) ?>