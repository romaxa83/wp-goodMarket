<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'GoodMarket';
?>

<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>GoodMarket</a>
    </div>
    <div class="login-box-body">
        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>
        <?php
        echo $form->field($model, 'username', [
                    'options' => ['class' => 'form-group has-feedback'],
                    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
                ])
                ->label(false)
                ->textInput(['placeholder' => $model->getAttributeLabel('username')]);
        ?>
        <?php
        echo $form->field($model, 'password', [
                    'options' => ['class' => 'form-group has-feedback'],
                    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
                ])
                ->label(false)
                ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]);
        ?>
        <div class="row">
            <div class="col-xs-8">
                <?php echo $form->field($model, 'rememberMe')->checkbox(); ?>
            </div>
            <div class="col-xs-4">
                <?php echo Html::submitButton('Войти', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
