<?php
/* @var $this yii\web\View */
/* @var $model backend\modules\content\models\ChannelCategory */
/* @var $seo backend\modules\content\models\SeoData */
/* @var $route backend\modules\content\models\SlugManager */
/* @var $channel backend\modules\content\models\Channel */
/* @var $content backend\modules\content\models\ChannelCategoryContent */

$this->title = $channel->title . ': создание новой категории';
$this->params['breadcrumbs'][] = ['label' => 'Типы записей', 'url' => ['/content/channel']];
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index', 'channel_id' => $model->channel_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="channel-category-create">
    <?= $this->render('_form', [
        'model' => $model,
        'seo' => $seo,
        'route' => $route,
        'action' => 'create',
        'content' => $content
    ]) ?>
</div>