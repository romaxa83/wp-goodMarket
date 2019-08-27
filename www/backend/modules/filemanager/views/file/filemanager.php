<?php

use backend\modules\filemanager\assets\FilemanagerAsset;
use backend\modules\filemanager\FileManager;
use backend\modules\filemanager\models\Tag;
use yii\helpers\ArrayHelper;
use yii\widgets\ListView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel pendalf89\filemanager\models\MediafileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['moduleBundle'] = FilemanagerAsset::register($this);
?>
<header id="header">
    <span class="glyphicon glyphicon-picture"></span> <?= FileManager::t('main', 'File manager') ?>
</header>
<div id="filemanager" class="d-flex" data-url-info="<?= Url::to(['file/info']) ?>">
    <?php
    $search = '<form action=' . Url::toRoute(['file/filemanager']) . '>
        <div class="input-group">
            <input type="text" name="search" class="form-control">
            <div class="input-group-btn">
                <button type="submit" class="btn btn-primary">Поиск</button>
            </div>
        </div>
    </form>';
    ?>
    <?php $searchForm = $this->render('_search_form', ['model' => $model]) ?>
    <?=
    ListView::widget([
        'dataProvider' => $dataProvider,
        'layout' => $search . '<div class="items">{items}</div>{pager}',
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(
                            Html::img('/admin' . $model->getDefaultThumbUrl($this->params['moduleBundle']->baseUrl))
                            . '<span class="checked glyphicon glyphicon-check"></span>', '#mediafile', ['data-key' => $key]
            );
        },
    ])
    ?>
    <div class="dashboard">
        <p><?= Html::a('<span class="glyphicon glyphicon-upload"></span> ' . FileManager::t('main', 'Upload manager'), ['file/uploadmanager'], ['class' => 'btn btn-default w-100']) ?></p>
        <div class="overflow-y-auto">
            <div id="tag-list">
                <div class="tag-link">
                    <?= Html::a('Все изображения', ['file/filemanager']); ?>
                </div>
                <?php foreach($tagList as $tag) : ?>
                    <div class="tag-link">
                        <?= Html::a($tag['name'], ['file/filemanager', 'MediafileSearch'=> ['tagIds' => $tag['id']]]); ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div id="fileinfo"></div>
            <div id="multiple-file"></div>
        </div>
    </div>
</div>
