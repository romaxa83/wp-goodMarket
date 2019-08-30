<?php

use app\modules\banners\BannersAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\modules\filemanager\widgets\FileInput;
use yii\bootstrap\ActiveForm;
use backend\widgets\langwidget\LangWidget;

BannersAsset::register($this);

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;

if (isset($model->id)) {
    $action = 'edit-banner?id=' . $model->id;
    $submit = 'Редактировать';
} else {
    $model->status = 0;
    $action = 'add-banner';
    $submit = 'Сохранить';
}
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Заполнение баннера</h3>
            </div>
            <div class="box-body">
                <?php $form = ActiveForm::begin(['id' => 'form-banner', 'method' => 'POST', 'action' => Url::to('/admin/banners/banners/' . $action)]); ?>
                <?php
                echo LangWidget::widget(['model' => $model, 'fields' => [
                        ['type' => 'text', 'name' => 'title'],
                        ['type' => 'text', 'name' => 'alias'],
                        ['type' => 'widget', 'name' => 'media_id', 'class' => 'backend\modules\filemanager\widgets\FileInput', 'options' => [
                                'buttonTag' => 'button',
                                'buttonName' => 'Browse',
                                'buttonOptions' => ['class' => 'btn btn-default'],
                                'options' => ['class' => 'form-control', 'readonly' => 'readonly'],
                                'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                                'thumb' => 'original',
                                'imageContainer' => '.img',
                                'pasteData' => FileInput::DATA_ID, // FileInput::DATA_URL
                                'callbackBeforeInsert' => 'function(e, data) {console.log( data );}',
                                'defaultTag' => 'banner',
                            ]],
                        ['type' => 'widget', 'name' => 'text', 'class' => 'vova07\imperavi\Widget', 'options' => [
                                'settings' => [
                                    'lang' => 'ru',
                                    'minHeight' => 200,
                                    'plugins' => [
                                        'clips',
                                        'fullscreen',
                                    ],
                                ]
                            ]
                        ]
                ]]);
                ?>
                <div class="preview-chosen-image" data-id="banners-media_id-btn">
                    <?php
//                    $media = $model->getMedia()->one();
//                    if (!is_null($media)) {
//                        if (!file_exists(Url::to('/admin' . $media->url))) {
//                            echo '<img src="' . Url::to('/admin' . $media->url) . '" alt="' . $media->alt . '">';
//                        } else {
//                            echo '<img src="' . Url::to('/admin/img/not-images.png') . '">';
//                        }
//                    }
                    ?>
                </div>
                <?php
                echo Html::beginTag('div') .
                Html::checkbox('status', $model->status, [
                    'id' => 'cd_' . $model->id,
                    'class' => 'tgl tgl-light publish-toggle status-toggle',
                    'data-id' => $model->id,
                    'data-url' => Url::to(['update-status']),
                ]) .
                Html::label('', 'cd_' . $model->id, ['class' => 'tgl-btn']) .
                Html::endTag('div');
                ?>
                <?php
//                echo $form->field($model, 'status')->inline()->radioList([1 => 'Да', 0 => 'Нет'], [
//                    'item' => function($index, $label, $name, $checked, $value) {
//                        $check = $checked ? ' checked="checked"' : 0;
//                        $return = '<label class="mr-15">';
//                        $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" ' . $check . ' class="custom-radio">';
//                        $return .= '<span>' . ucwords($label) . '</span>';
//                        $return .= '</label>';
//                        return $return;
//                    }
//                ]);
                ?>
                <div class="form-group">
                    <?php echo Html::submitButton($submit, ['class' => 'btn btn-primary', 'name' => 'save']) ?>
                    <a href="<?php echo Url::to(['/banners/banners']) ?>" class="btn btn-danger">Отмена</a>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
