<?php

/* @var $this yii\web\View */
/* @var $model backend\modules\blog\forms\TagForm */

$this->title = 'Создать тег';
$this->params['breadcrumbs'][] = ['label' => 'Список тегов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tag-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
