<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\content\models\ChannelCategory */
/* @var $seo backend\modules\content\models\SeoData */
/* @var $route backend\modules\content\models\SlugManager */
/* @var $content backend\modules\content\models\ChannelCategoryContent */

$this->title = $model->channel->title . ': обновить категорию - ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Типы записей', 'url' => ['/content/channel']];
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index', 'channel_id' => $model->channel_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="channel-category-update">
    <?= $this->render('_form', [
        'model' => $model,
        'seo' => $seo,
        'route' => $route,
        'content' => $content
    ]) ?>
</div>