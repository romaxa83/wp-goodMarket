<?php

/* @var $this yii\web\View */
/* @var $tag backend\modules\blog\entities\Tag */
/* @var $model backend\modules\blog\forms\TagForm */

$this->title = 'Редактирование тега: ' . $langModel->languageData['ru']['title'];
$this->params['breadcrumbs'][] = ['label' => 'Список тегов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $langModel->languageData['ru']['title'], 'url' => ['view', 'id' => $model->_tag->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="tag-update">

    <?= $this->render('_form', [
            'model' => $model,
            'langModel' => $langModel
        ]); ?>

</div>
