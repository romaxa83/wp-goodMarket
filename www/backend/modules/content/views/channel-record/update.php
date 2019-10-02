<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\content\models\ChannelRecord */
/* @var $structure string */
/* @var $seo backend\modules\content\models\SeoData */
/* @var $route backend\modules\content\models\SlugManager */
/* @var $categories backend\modules\content\models\ChannelCategory[] */

$this->title = 'Обновить запись: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Типы записей', 'url' => ['/content/channel']];
$this->params['breadcrumbs'][] = ['label' => 'Записи', 'url' => ['/content/channel-record', 'channel_id' => $model->channel_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="channel-record-update">
    <?= $this->render('_form', [
        'model' => $model,
        'seo' => $seo,
        'route' => $route,
        'structure' => $structure,
        'categories' => $categories,
        'action' => 'update',
    ]) ?>
</div>