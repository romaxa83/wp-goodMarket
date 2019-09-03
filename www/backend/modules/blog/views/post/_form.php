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
                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6">
                                <?= $form->field($model, 'title')->textInput(['class' => 'form-control title-translit'])->label('Название поста'); ?>
                            </div>
                            <!-- Alias -->
                            <div class="col-md-6">
                                <?= $form->field($model, 'alias')->textInput(['class' => 'form-control alias-translit'])->label('Алиас'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <!-- Category -->
                                    <div class="col-lg-6">
                                        <?= $form->field($model, 'category_id')
                                            ->dropDownList($model->categoriesList(),['prompt' => 'Выберите категорию'])
                                            ->label('Категория'); ?>
                                    </div>
                                </div>

                            </div>
                            <!-- StatusPublish -->
                            <div class="col-md-2">
                                <div class="box-custome-checkbox">
                                    <?= Html::label('Опубликовать', 'status', ['class' => 'tgl-btn']) . Html::checkbox('PostForm[status]', $model->status, [
                                            'id' => 'status',
                                            'class' => 'tgl tgl-light custome-checkbox',
                                            'value' => ($model->status) ? $model->status : 0,
                                        ]) . Html::label('', 'status', ['class' => 'tgl-btn']); ?>
                                </div>
                            </div>
                            <!-- DatePublish -->
                            <div class="col-md-4">
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
                            <!-- Tags -->
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
                            <!-- Image -->
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
                        <div class="row">
                            <!-- Content -->
                            <div class="col-md-6">
                                <?= $form->field($model, 'content')->widget(TM::className(), [
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
                                ])->label('Контент'); ?>
                            </div>
                            <!-- Description -->
                            <div class="col-md-6">
                                <?= $form->field($model, 'description')->widget(TinyMce::className(), [
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
                                ])->label('Описание'); ?>
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

