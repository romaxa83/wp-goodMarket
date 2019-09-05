<?php

use app\modules\blog\BlogAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\widgets\langwidget\LangWidget;
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
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Основные поля</h3>
                            </div>
                            <div class="box-body">
                                <?= LangWidget::widget([
                                    'model' => $langModel,
                                    'fields' => [
                                        ['type' => 'text', 'name' => 'title'],
                                    ]
                                ]); ?>

                                <?= $form->field($model, 'alias')->textInput(['class' => 'form-control alias-translit','maxlength' => true])->label('Алиас тега') ?>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Сохранить',['class' => 'btn btn-success mr-15',]) ?>
                    <?= Html::resetButton('Сбросить', ['class' => 'btn btn-danger mr-15']) ?>
                    <a href="<?= Url::to(['index']) ?>" class="btn btn-primary">Вернуться к списку</a>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
