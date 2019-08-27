<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\users\administrators\AdministratorsAsset;
use common\controllers\AccessController;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\users\administrators\models\AdministratorsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Администраторы';
$this->params['breadcrumbs'][] = $this->title;

AdministratorsAsset::register($this);

?>
<div class="user-index">
    <div class="row mb-15">
        <div class="col-xs-12">
            <?php
                if(AccessController::isView(Yii::$app->controller, 'create')){
                    echo Html::a('Создать администратора', ['create'], ['class' => 'btn btn-primary mr-15']);
                }
            ?>
        </div>
    </div>

    <div class="box">
        <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => [
                'class' => 'table table-hover'
            ],
            'columns' => [
//                ['class' => 'yii\grid\CheckboxColumn',
//                    'name' => 'delete',
//                    'cssClass'=>'mass-checked',
//                    'checkboxOptions'=>function ($model, $key, $index){
//                        return [
//                            'class' => 'custom-checkbox',
//                            'data-id' => $model->id
//                        ];
//                    },
//                    'contentOptions' => function($model, $key, $index, $column) {
//                        return [
//                            'class' => 'element-check check-del',
//                        ];
//                    },
//                    'header' => Html::checkBox(
//                        'delete_all',
//                        false,
//                        [
//                            'id' => 'selectAllElements',
//                            'type' => 'checkbox',
//                            'class' => 'custom-checkbox select-on-check-all'
//                        ]
//                    ),
//                    'headerOptions' => [
//                        'width' => '34',
//                        'class' => 'select-all-header'
//                    ]
//                ],
                'id',
                'first_name',
                'last_name',
                'username',
                'email:email',
                'roleName',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Управление',
                    'headerOptions' => [
                        'width' => '110'
                    ],
                    'template' => '{view} {update} {user} {delete}',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            if(AccessController::checkPermission($url)){
                                return Html::a('<span class="fa fa-eye"></span>', ['/users/administrators/administrators/view', 'id' => $model->id], ['class' => 'grid-option', 'title' => 'Просмотр']);
                            }
                        },
                        'update' => function ($url, $model, $key) {
                            if(AccessController::checkPermission($url)){
                                return Html::a('<span class="fa fa-pencil"></span>', ['/users/administrators/administrators/update', 'id' => $model->id], ['class' => 'grid-option', 'title' => 'Редактировать пользователя']);
                            }
                        },
                        'delete' => function ($url, $model, $key) {
                            if(AccessController::checkPermission($url)){
                                return Html::a('<span class="fa fa-trash"></span>', ['/users/administrators/administrators/delete', 'id' => $model->id], [
                                    'class' => 'grid-option',
                                    'title' => 'Удалить пользователя',
                                    'data-pjax' => '0',
                                    'data-confirm' => 'Вы действительно хотите удалить пользователя: '. $model->first_name .' '. $model->last_name .'. Всё связаные данные будут потеряны ',
                                    'data-method' => 'post'
                                ]);
                            }
                        },
                    ],
                ],
            ],
        ]); ?>
        </div>
    </div>
</div>
