<?php

/* @var $this yii\web\View */
/* @var $category backend\modules\blog\entities\Category */
/* @var $model backend\modules\blog\forms\CategoryForm */

$this->title = 'Редактирование категории: ' . $category->title;
$this->params['breadcrumbs'][] = ['label' => 'Список категорий', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => ['view', 'id' => $category->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
