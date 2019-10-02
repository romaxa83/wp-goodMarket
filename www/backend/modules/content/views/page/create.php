<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\content\models\Page */
/** @var $seo backend\modules\content\models\PageMeta */
/** @var $slug backend\modules\content\models\SlugManager */

$this->title = 'Создать новую страницу';
$this->params['breadcrumbs'][] = ['label' => 'Страницы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-create">
    <?= $this->render('_form', [
        'model' => $model,
        'langModel' => $langModel,
        'seo' => $seo,
        'langSeo' => $langSeo,
        'slug' => $slug,
    ]) ?>

</div>
