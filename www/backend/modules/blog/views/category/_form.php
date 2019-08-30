<?php

use backend\modules\blog\entities\Category;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\blog\BlogAsset;

/* @var $this yii\web\View */
/* @var $model backend\modules\blog\forms\CategoryForm*/
/* @var $form yii\widgets\ActiveForm */

BlogAsset::register($this);
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="post-category-form">
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
                                <?= $form->field($model, 'title')->textInput(['class' => 'form-control title-translit'])->label('Название категории'); ?>

                                <?= $form->field($model, 'alias')->textInput(['class' => 'form-control alias-translit','maxlength' => true])->label('Аллиас') ?>

                                <?= $form->field($model, 'parent_id')->dropDownList(
                                    $model->categoriesList()
                                )->label('Родительская категория')->hint('Если вы создаете родительскую категорию,выбирать не надо') ?>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="form-group">
                    <?= Html::submitButton('Сохранить',['class' => 'btn btn-primary mr-15',]) ?>
                    <?= Html::resetButton('Сбросить', ['class' => 'btn btn-primary mr-15']) ?>
                    <a href="<?= Url::to(['index']) ?>" class="btn btn-primary">Вернуться к списку</a>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
