<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\modules\users\roles\models\AuthItem;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Форма для заполнения</h3>
        </div>
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($user, 'username')->textInput() ?>

            <?= $form->field($user, 'first_name')->textInput() ?>

            <?= $form->field($user, 'last_name')->textInput() ?>

            <?= $form->field($user, 'email')->textInput() ?>

            <?php if (Yii::$app->controller->action->id == 'create'): ?>
                <?= $form->field($user, 'password_hash', [
                    'template' => '{label}<div class="input-group">
                <div class="input-group-addon password_eye" style="cursor: pointer">
                    <i class="fa fa-eye"></i>
                </div>{input}
            </div>{hint}{error}',
                ])->passwordInput(['value' => ''])->label('Пароль*') ?>
            <?php else: ?>
                <?= $form->field($user, 'new_password', [
                    'template' => '{label}<div class="input-group">
                <div class="input-group-addon password_eye" style="cursor: pointer">
                    <i class="fa fa-eye"></i>
                </div>{input}
            </div>{hint}{error}',
                ])->passwordInput(['value' => ''])->label('Новый пароль') ?>
            <?php endif; ?>

            <?= $form->field($user, 'phone', [
                'template' => '{label}<div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-phone"></i>
                </div>{input}
            </div>{hint}{error}',
            ])->textInput(['data-inputmask' => '"mask": "+38 (099) 999-9999"', 'data-mask' => '']) ?>

            <div class="form-group">
                <?php if (Yii::$app->controller->action->id == 'create'): ?>
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success mr-15', 'name' => 'save', 'value' => 'create']) ?>
                    <?= Html::submitButton('Сохранить и выйти в список', ['class' => 'btn btn-success mr-15', 'name' => 'save', 'value' => 'index']) ?>
                    
                <?php else: ?>
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success mr-15', 'name' => 'save', 'value' => '/admin/users/people/people-list/update?id='.$user->id]) ?>
                <?php endif; ?>
                <?= Html::a('Отменить', ['/users/people/people-list/index'], ['class' => 'btn btn-danger mr-15']) ?>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
