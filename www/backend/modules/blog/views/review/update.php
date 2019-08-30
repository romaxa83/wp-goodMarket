<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model backend\modules\referenceBooks\models\HotelReview*/
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Редактирование отзыва';
$this->params['breadcrumbs'][] = ['label' => 'Список отзывов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="review-for-hotel-form">
    <div class="row">
        <?php $form = ActiveForm::begin(); ?>
        <div class="col-xs-6">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-12">
                            <?= $form->field($model, 'title')->textInput(['class' => 'form-control title-translit'])->label('Название обзора'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Description -->
                        <div class="col-md-12">
                            <?= $form->field($model, 'comment')->widget(TinyMce::className(), [
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
            <div class="form-group">
                <?= Html::submitButton('Сохранить',['class' => 'btn btn-primary mr-15',]) ?>
                <a href="<?php echo Url::to(['index']) ?>" class="btn btn-primary">Вернуться к списку</a>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

