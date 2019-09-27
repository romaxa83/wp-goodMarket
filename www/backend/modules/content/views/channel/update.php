<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\content\models\Channel */
/* @var $seo backend\modules\content\models\SeoData */
/* @var $route backend\modules\content\models\SlugManager */
/* @var $contentBlocks backend\modules\content\models\ChannelContent */
/* @var $commonFields backend\modules\content\models\ChannelRecordsCommonField */

$this->title = 'Обновить тип записи: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Типы записей', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="channel-update">
    <?= $this->render('_form', [
        'model' => $model,
        'seo' => $seo,
        'route' => $route,
        'contentBlocks' => $contentBlocks,
        'commonFields' => $commonFields,
        'action' => 'update',
    ]) ?>

</div>