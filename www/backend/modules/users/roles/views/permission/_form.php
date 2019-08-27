<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\controllers\AccessController;
/* @var $this yii\web\View */
/* @var $model backend\modules\users\roles\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
//$model->data = unserialize($model->data);
?>

<div class="auth-item-form">

    <div class="box">

        <div class="box-header with-border">
            <h3 class="box-title">Форма для заполнения</h3>
        </div>

        <div class="box-body">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Имя разрешения') ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        
        <?php if(AccessController::isView(Yii::$app->controller, 'ajax-load-routes-table')):?>
            <?php echo $this->render('manage_actions_table', array_merge($structure_elements, ['permission_routes' => $permission_routes]))?>
            <div class="table-responsive-md">
                <?php echo $this->render('actions_table', ['dataProvider' => $dataProvider])?>        
            </div>
        <?php endif;?>
        
        <div class="form-group">
            <?php if($model->name):?>
                <?= Html::submitButton('Редактировать', ['class' => 'btn btn-primary mr-15']) ?>
                <?= Html::a('Отменить',['/users/roles/permission/index'] ,['class' => 'btn btn-danger mr-15']) ?>
            <?php else:?>
                <?= Html::submitButton('Создать', ['class' => 'btn btn-primary mr-15']) ?>
                <?= Html::a('Отменить',['/users/roles/permission/index'] ,['class' => 'btn btn-danger mr-15']) ?>
            <?php endif;?>
        </div>

        <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
