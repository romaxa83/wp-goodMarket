<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\users\administrators\AdministratorsAsset;
use backend\modules\users\roles\models\AuthItem;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

AdministratorsAsset::register($this);
?>

<div class="user-form">

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Форма для заполнения</h3>
        </div>
        <div class="box-body">
        <?php $form = ActiveForm::begin(); ?>

        <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'username')->textInput() ?>

            <?= $form->field($model, 'first_name')->textInput() ?>

            <?= $form->field($model, 'last_name')->textInput() ?>

            <?= $form->field($model, 'email')->textInput() ?>

            <?php if (Yii::$app->controller->action->id == 'create'): ?>
                <?= $form->field($model, 'password_hash', [
                    'template' => '{label}<div class="input-group">
                <div class="input-group-addon password_eye" style="cursor: pointer">
                    <i class="fa fa-eye"></i>
                </div>{input}
            </div>{hint}{error}',
                ])->passwordInput(['value' => ''])->label('Пароль*') ?>
            <?php else: ?>
                <?= $form->field($model, 'new_password', [
                    'template' => '{label}<div class="input-group">
                    <div class="input-group-addon password_eye" style="cursor: pointer">
                        <i class="fa fa-eye"></i>
                    </div>{input}
                </div>{hint}{error}',
                ])->passwordInput(['value' => ''])->label('Новый пароль') ?>
            <?php endif; ?>

            <?= Html::dropDownList(
                    'AuthItem[role_name]',
                    $model->getRoleName(),
                    $roles,
                    ['class' => 'form-group form-control', 'prompt' => 'Выбирите роль']
                );
            ?>

            <div class="form-group">
            <?php if (Yii::$app->controller->action->id == 'create'):?>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success mr-15', 'name' => 'save', 'value' => 'create']) ?>
                <?= Html::a('Отменить',['/users/administrators/administrators/index'] ,['class' => 'btn btn-danger mr-15']) ?>
            <?php else:?>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success mr-15', 'name' => 'save', 'value' => '/admin/users/administrators/administrators/update?id='.$model->id]) ?>
                <?= Html::a('Отменить',['/users/administrators/administrators/index'] ,['class' => 'btn btn-danger mr-15']) ?>
            <?php endif;?>
        </div>

        <?php ActiveForm::end(); ?>

        </div>  

        </div>
    </div>

</div>
