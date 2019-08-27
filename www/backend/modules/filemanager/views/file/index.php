<?php

use backend\modules\filemanager\FileManager;
use backend\modules\filemanager\assets\ModalAsset;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = FileManager::t('main', 'Files');
$this->params['breadcrumbs'][] = ['label' => FileManager::t('main', 'File manager'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;

ModalAsset::register($this);
?>
<iframe src="<?= Url::to(['file/filemanager']) ?>" id="post-original_thumbnail-frame" frameborder="0" role="filemanager-frame"></iframe>