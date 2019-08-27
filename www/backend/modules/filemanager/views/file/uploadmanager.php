<?php
use dosamigos\fileupload\FileUploadUI;
use backend\modules\filemanager\FileManager;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel pendalf89\filemanager\models\Mediafile */

?>

<header id="header"><span class="glyphicon glyphicon-upload"></span> <?= FileManager::t('main', 'Upload manager') ?></header>

<div id="uploadmanager">
    <?= Html::hiddenInput('tag', '', ['id' => 'filemanager-tagIds']) ?>
    <?= FileUploadUI::widget([
        'model' => $model,
        'attribute' => 'file',
        'clientOptions' => [
            'autoUpload'=> Yii::$app->getModule('filemanager')->autoUpload,
        ],
        'clientEvents' => [
            'fileuploadsubmit' => "function (e, data) { data.formData = [{name: 'tagIds', value: $('#filemanager-tagIds').val()}];}",
            'fileuploadprocessstart' => "function (e, data) {
            $('.start-all').removeClass('d-none');
            $('.cancel-all').removeClass('d-none');
            }",
            'fileuploadstop' => "function (e, data) {
            $('.delete-all').removeClass('d-none');
            $('.file-check').removeClass('d-none');
            $('.start-all').addClass('d-none');
            $('.cancel-all').addClass('d-none');
            }",
        ],
        'url' => ['upload'],
        'gallery' => false,
        'formView' => '/file/_upload_form',
        'uploadTemplateView' => '/file/_upload_template',
        'downloadTemplateView' => '/file/_download_template'
    ]) ?>
</div>
