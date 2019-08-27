<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\users\roles\models\AuthItem */

$this->title = $roles->name;
$this->params['breadcrumbs'][] = ['label' => 'Список ролей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-view">
    <div class="row mb-15">
        <div class="col-xs-12">
            <?= Html::a('Редактирование', ['update', 'name' => $roles->name], ['class' => 'btn btn-primary mr-15']) ?>
            <?= Html::a('Удаление', ['delete', 'name' => $roles->name], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $roles,
        'attributes' => [
            [
                'attribute' => 'name',
                'format' => 'text',
                'label' => 'Имя роли'
            ],
            'type',
            'description:text',
            'created_at:datetime',
            'updated_at:datetime',
            [
                'format' => 'raw',
                'label' => 'Разрешения',
                'value' => function ($model) {
                    $permissions = $model->authItemChildren;
                    $text = '';
                    foreach ($permissions as $permission){
                        $text .= $permission->child.', ';
                    }
                    return $text;

                }
            ],
        ],
    ]) ?>

</div>
