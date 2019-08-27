<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\users\roles\models\AuthItem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Список разрешений', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-view">

    <div class="row mb-15">
        <div class="col-xs-12">
            <?= Html::a('Редактирование', ['update', 'name' => $model->name], ['class' => 'btn btn-primary mr-15']) ?>
            <?= Html::a('Удаление', ['delete', 'name' => $model->name], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы действительно хотите удалить это разрешение?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Данные разрешения</h3>
        </div>
        <div class="box-body">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'name',
                    'format' => 'text',
                    'label' => 'Имя разрешения'
                ],
                'type',
                'description:ntext',
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
        </div>
    </div>

</div>
