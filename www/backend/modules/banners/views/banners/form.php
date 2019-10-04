<?php

use app\modules\banners\BannersAsset;
use backend\modules\banners\models\Banner;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\modules\filemanager\widgets\FileInput;
use yii\bootstrap\ActiveForm;
use backend\widgets\langwidget\LangWidget;
use backend\modules\banners\models\BannerLang;

BannersAsset::register($this);
?>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">
                <?php
                $form = ActiveForm::begin(['id' => 'form-banner', 'method' => 'POST']);
                echo LangWidget::widget(['model' => $modelLang, 'fields' => [
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
                                'pasteData' => FileInput::DATA_ID,
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
                echo $form->field($model, 'type')->widget(Select2::classname(), [
                    'id' => 'type',
                    'name' => 'Banner[type]',
                    'data' => Banner::BANNER_TYPES,
                    'value' => $model->type,
                    'options' => ['prompt' => 'Select a type ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                echo $form->field($model, 'status')->inline()->radioList([1 => ' Да', 0 => ' Нет'], [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $check = $checked ? ' checked="checked"' : 0;
                        $return = '<label class="mr-15">';
                        $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" ' . $check . ' class="custom-radio">';
                        $return .= '<span>' . ucwords($label) . '</span>';
                        $return .= '</label>';
                        return $return;
                    }
                ]);
                echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'style' => 'margin-right: 10px;']);
                echo Html::a('Отмена', Url::to(['/banners/banners']), ['class' => 'btn btn-danger']);
                ActiveForm::end();
                ?>
            </div>
        </div>
    </div>
</div>
