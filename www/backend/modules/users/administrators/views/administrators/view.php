<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Администраторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <div class="row mb-15">
        <div class="col-xs-12">
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary mr-15']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Данные пользователя</h3>
        </div>
        <div class="box-body">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'username',
    //            'auth_key',
    //            'password_hash',
    //            'password_reset_token',
                'email:email',
                [
                    'label' => 'Роль',
                    'format' => 'raw',
                    'value' => function($model){
                    return $model->getRoleName();
                    }
                ],
    //            'status',
    //            'created_at',
    //            'updated_at',
            ],
        ]) ?>
        </div>
    </div>

</div>
