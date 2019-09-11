<?php

/* @var $this yii\web\View */
/* @var $model backend\modules\blog\forms\CategoryForm */

$this->title = 'Создать категорию';
$this->params['breadcrumbs'][] = ['label' => 'Список категорий', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-category-create">

    <?= $this->render('_form', [
        'model' => $model,
        'langModel' => $langModel
    ]) ?>

</div>
