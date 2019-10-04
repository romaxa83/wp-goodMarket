<?php
/* @var $this yii\web\View */
/* @var $model backend\modules\content\models\Page */
/* @var $seo backend\modules\content\models\Page */
/* @var $slug backend\modules\content\models\SlugManager */
/* @var $textBlocks[] backend\models\Page */

$this->title = 'Обновить страницу: ' . $model['manyLang'][0]->title;
$this->params['breadcrumbs'][] = ['label' => 'Страницы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model['manyLang'][0]->title;
?>
<div class="page-update">

    <?= $this->render('_form', [
        'model' => $model,
        'langModel' => $langModel,
        'seo' => $seo,
        'langSeo' => $langSeo,
        'textBlocks' => $textBlocks,
        'slug' => $slug,
    ]) ?>

</div>
