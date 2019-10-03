<?php
/** @var int $id */
/** @var string $group */

use backend\modules\category\models\Category;
use kartik\select2\Select2;

?>

<?= Select2::widget([
    'name' => "{$group}[{$id}][category_id]",
    'data' => array_map(function($category) {
        return $category['name'];
    }, Category::getListCategory()),
    'options' => [
        'placeholder' => 'Выбор категории',
        'class' => 'form-control',
        'id' => "{$group}-{$id}",
    ]
]) ?>