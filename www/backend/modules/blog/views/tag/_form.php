<?php

use app\modules\blog\BlogAsset;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\blog\forms\TagForm */
/* @var $form yii\widgets\ActiveForm */
BlogAsset::register($this);
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="tag-form">
    <div class="row">
        <div class="col-xs-12">
            <?php $form = ActiveForm::begin(); ?>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Основные поля</h3>
                            </div>
                            <div class="box-body">
                                <?= $form->field($model, 'title')->textInput(['class' => 'form-control title-translit','maxlength' => true])->label('Название тега')?>

                                <?= $form->field($model, 'alias')->textInput(['class' => 'form-control alias-translit','maxlength' => true])->label('Алиас тега') ?>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Сохранить',['class' => 'btn btn-primary mr-15',]) ?>
                    <?= Html::resetButton('Сбросить', ['class' => 'btn btn-primary mr-15']) ?>
                    <?= Html::a('Вернуться', Yii::$app->request->referrer, ['class' => 'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
