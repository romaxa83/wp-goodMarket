<?php

/* @var $this yii\web\View */
/* @var $tag backend\modules\blog\entities\Tag */
/* @var $model backend\modules\blog\forms\TagForm */

$this->title = 'Редактирование тега: ' . $tag->title;
$this->params['breadcrumbs'][] = ['label' => 'Список тегов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $tag->title, 'url' => ['view', 'id' => $tag->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="tag-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
