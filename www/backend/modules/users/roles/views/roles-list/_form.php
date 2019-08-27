<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\users\roles\models\AuthItem;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\modules\users\roles\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="auth-item-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Форма для заполнения</h3>
        </div>
        <div class="box-body">

            <?php echo $form->field($model, 'name')->textInput(['maxlength' => true])->label('Имя роли') ?>

            <?php echo $form->field($model, 'description')->textarea(['rows' => 6]) ?>

            <h4>Разрешения:</h4>
            <?php 
                echo Select2::widget([
                    'name' => 'role_permissions',
                    'value' => $role_permissions, // initial value
                    'data' => $permissions,
                    'maintainOrder' => true,
                    'options' => ['placeholder' => 'Выберите разрешения для роли', 'multiple' => true],
                    'pluginOptions' => [
                        'tags' => true
                        //'maximumInputLength' => 10
                    ],
                ]);
            ?>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <?php if ($model->name): ?>
                <?php echo Html::submitButton('Редактировать', ['class' => 'btn btn-success mr-15', 'name' => 'save', 'value' => '/admin/users/roles/roles-list/update?name='.$model->name]) ?>
            <?php else: ?>
                <?php echo Html::submitButton('Сохранить', ['class' => 'btn btn-success mr-15', 'name' => 'save', 'value' => 'index']) ?>
                <?php echo Html::submitButton('Сохранить и создать новую', ['class' => 'btn btn-success mr-15', 'name' => 'save', 'value' => 'create']) ?>
            <?php endif; ?>
            <?= Html::a('Отменить', ['/users/roles/roles-list/index'], ['class' => 'btn btn-danger mr-15']) ?>
        </div>

    </div>
</div>
<?php ActiveForm::end(); ?>
</div>
