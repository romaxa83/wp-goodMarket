<?php

use yii\helpers\Html;
use backend\modules\filemanager\FileManager;
use backend\modules\filemanager\assets\FilemanagerAsset;
use common\controllers\AccessController;

/* @var $this yii\web\View */

$this->title = FileManager::t('main', 'File manager');
$this->params['breadcrumbs'][] = $this->title;

$assetPath = FilemanagerAsset::register($this)->baseUrl;
?>

<div class="filemanager-default-index">
    <h1><?= FileManager::t('main', 'File manager module'); ?></h1>

    <div class="row">
        <div class="col-md-6">

            <div class="text-center">
                <h2>
                    <?= Html::a(FileManager::t('main', 'Files'), ['file/index']) ?>
                </h2>
                <?= Html::a(
                    Html::img($assetPath . '/images/files.png', ['alt' => 'Files'])
                    , ['file/index']
                ) ?>
            </div>
        </div>

        <div class="col-md-6">

            <div class="text-center">
                <h2>
                    <?= Html::a(FileManager::t('main', 'Settings'), ['default/settings']) ?>
                </h2>
                <?= Html::a(
                    Html::img($assetPath . '/images/settings.png', ['alt' => 'Tools'])
                    , ['default/settings']
                ) ?>
            </div>
        </div>
    </div>
</div>