<?php


use backend\modules\category\models\Category;
use kartik\select2\Select2;

/** @var int $block_id */
/** @var string $value */
/** @var string $group */
?>
<?= Select2::widget([
    'name' => "{$group}[{$block_id}][category_id]",
    'data' => array_map(function($category) {
        return $category['name'];
    }, Category::getListCategory()),
    'value' => $value,
    'options' => [
        'placeholder' => 'Выбор категории',
        'class' => 'form-control',
    ]
]) ?>