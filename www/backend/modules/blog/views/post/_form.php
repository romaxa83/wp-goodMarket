<?php

use kartik\datetime\DateTimePicker;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;
use app\modules\blog\BlogAsset;
use backend\modules\filemanager\widgets\FileInput;
use backend\modules\filemanager\widgets\TinyMce as TM;
use backend\widgets\langwidget\LangWidget;
/* @var $this yii\web\View */
/* @var $model backend\modules\blog\forms\PostForm*/
/* @var $post backend\modules\blog\entities\Post*/
/* @var $form yii\widgets\ActiveForm */
/* @var $options */

BlogAsset::register($this);
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="post-form">
    <div class="row">
        <?php $form = ActiveForm::begin(); ?>
        <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <!-- multi-lang  -->
                        <div class="row">
                            <div class="col-md-12">
                                <?= LangWidget::widget([
                                        'model' => $langModel,
                                        'fields' => [
                                            ['type' => 'text', 'name' => 'title'],
                                            ['type' => 'widget', 'name' => 'content', 'class' => 'backend\modules\filemanager\widgets\TinyMce', 'options' => [
                                                'options' => ['rows' => 6, 'class' => 'field-tiny-mce','defaultTag' => 'blog'],
                                                'callbackBeforeInsert' => 'function(e,data){data.url = "/admin" + data.url }',
                                                'clientOptions' => [
                                                    'language' => 'ru',
                                                    'image_dimensions' => true,
                                                    'plugins' => [
                                                        "advlist autolink lists link image charmap print preview anchor",
                                                        "searchreplace visualblocks code fullscreen",
                                                        "insertdatetime media table contextmenu paste"
                                                    ],
                                                    'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
                                                ]
                                            ]],
                                            ['type' => 'widget', 'name' => 'description', 'class' => 'backend\modules\filemanager\widgets\TinyMce', 'options' => [
                                                'options' => ['rows' => 6, 'class' => 'field-tiny-mce','defaultTag' => 'blog'],
                                                'callbackBeforeInsert' => 'function(e,data){data.url = "/admin" + data.url }',
                                                'clientOptions' => [
                                                    'language' => 'ru',
                                                    'image_dimensions' => true,
                                                    'plugins' => [
                                                        "advlist autolink lists link image charmap print preview anchor",
                                                        "searchreplace visualblocks code fullscreen",
                                                        "insertdatetime media table contextmenu paste"
                                                    ],
                                                    'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
                                                ]
                                            ]],
                                        ]
                                    ]); ?>
                            </div>
                        </div>
                        <!-- single -->
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'category_id')->dropDownList($model->categoriesList(),['prompt' => 'Выберите категорию'])->label('Категория'); ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'alias')->textInput(['class' => 'form-control alias-translit'])->label('Алиас'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model->tags, 'existing')->widget(Select2::classname(), [
                                        'data' => $model->tags->tagsList(),
                                        'language' => 'ru',
                                        'maintainOrder' => true,
                                        'options' => [
                                            'placeholder' => 'Выберите тег или создайте новый',
                                            'multiple' => true
                                        ],
                                        'pluginOptions' => [
                                            'tags' => true,
                                            'tokenSeparators' => [',', ' '],
                                            'maximumInputLength' => 10,
                                            'allowClear' => true
                                        ],
                                    ])->label('Теги') ?>
                            </div>
                            <div class="col-md-1">
                                <div class="box-custome-checkbox">
                                    <?= Html::label('Опубликовать', 'status', ['class' => 'tgl-btn']) . Html::checkbox('PostForm[status]', $model->status, [
                                            'id' => 'status',
                                            'class' => 'tgl tgl-light custome-checkbox',
                                            'value' => ($model->status) ? $model->status : 0,
                                        ]) . Html::label('', 'status', ['class' => 'tgl-btn']); ?>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <?= $form->field($model, 'published_at')->widget(DateTimePicker::classname(), [
                                    'options' => ['placeholder' => 'Выберить дату публикации'],
                                    'language' => 'ru',
                                    'readonly' => $model->status == 1?true:false,
                                    'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
                                    'layout' => '{picker}{input}{remove}',
                                    'removeButton' => ['position' => 'append'],
                                    'convertFormat' => false,
                                    'pluginOptions' => [
                                        'todayBtn' => true,
                                        'format' => 'dd-mm-yyyy hh:ii',
                                        'timezone' => 'Europe/Kiev',
                                        'autoclose' => true,
                                        'weekStart'=>1
                                    ]
                                ])->label('Дата публикации'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'media_id')->widget(FileInput::className(), [
                                    'buttonTag' => 'button',
                                    'buttonName' => 'Browse',
                                    'buttonOptions' => ['class' => 'btn btn-default'],
                                    'options' => ['class' => 'form-control', 'readonly' => 'readonly'],
                                    'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                                    'thumb' => 'original',
                                    'imageContainer' => '.img',
                                    'pasteData' => FileInput::DATA_ID
                                ])->label('Обложка'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- SEO -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Seo данные</h3>
                        </div>
                        <div class="box-body">
                            <div class="box-body">
                                <?= $form->field($model->meta, 'h1')->textInput() ?>
                                <?= $form->field($model->meta, 'title')->textInput() ?>
                                <?= $form->field($model->meta, 'keywords')->textInput() ?>
                                <?= $form->field($model->meta, 'description')->textarea(['row' => 3]) ?>

                                <?= $form->field($model->meta, 'seo_text')->widget(TinyMce::className(), [
                                    'options' => ['rows' => 6, 'class' => 'field-tiny-mce'],
                                    'language' => 'ru',
                                    'clientOptions' => [
                                        'plugins' => [
                                            "advlist autolink lists link charmap print preview anchor",
                                            "searchreplace visualblocks code fullscreen",
                                            "insertdatetime media table contextmenu paste"
                                        ],
                                        'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
                                    ]
                                ])?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton('Сохранить',['class' => 'btn btn-primary mr-15',]) ?>
                <?= Html::resetButton('Сбросить', ['class' => 'btn btn-primary mr-15']) ?>
                <a href="<?php echo Url::to(['index']) ?>" class="btn btn-primary">Вернуться к списку</a>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>