<?php

/* @var $this yii\web\View */
/* @var $model backend\modules\blog\forms\PostForm */

$this->title = 'Создать пост';
$this->params['breadcrumbs'][] = ['label' => 'Список постов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-create">

    <?= $this->render('_form', [
        'model' => $model,
        'options' => ['optio']
    ]) ?>

</div>
