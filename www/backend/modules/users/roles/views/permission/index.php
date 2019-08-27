<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\controllers\AccessController;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\users\roles\models\PermissionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Разрешения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-index">

<!--    --><?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="row mb-15">
        <div class="col-xs-12">
            <?php 
                if(AccessController::isView(Yii::$app->controller, 'create')){
                    echo Html::a('Создать разрешение', ['create'], ['class' => 'btn btn-primary']);
                }
            ?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Список разрешений</h3>
        </div>
        <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => [
                'class' => 'table table-hover'
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'name',
                    'format' => 'text',
                    'label' => 'Имя разрешения'
                ],
    //            'type',
                'description:ntext',
    //            'rule_name',
                // [
                //     'attribute' => 'data',
                //     'format' => 'text',
                //     'value' => function($model){
                //         return unserialize($model->data);
                //     }
                // ],
                'created_at:datetime',
                //'updated_at',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header'=>'Управление',
                    'headerOptions' => [
                        'width' => '110'
                    ],
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            $access = AccessController::isView(Yii::$app->controller, 'view');
                            if($access){
                                return Html::a('<span class="fa fa-eye"></span>', ['/users/roles/permission/view', 'name' => $key], [
                                    'title' => 'Просмотр',
                                    'class' => 'grid-option'
                                ]);
                            }
                        },
                        'update' => function ($url, $model, $key) {
                            $access = AccessController::isView(Yii::$app->controller, 'update');
                            if($access){
                                return Html::a('<span class="fa fa-pencil"></span>', ['/users/roles/permission/update', 'name' => $key], [
                                    'title' => 'Редактировать разрешение',
                                    'class' => 'grid-option'
                                ]);
                            }
                        },
                        'delete' => function ($url, $model, $key) {
                            $access = AccessController::isView(Yii::$app->controller, 'delete');
                            if($access){
                                return Html::a('<span class="fa fa-trash"></span>', ['/users/roles/permission/delete', 'name' => $key], [
                                    'title' => 'Удалить разрешение',
                                    'class' => 'grid-option',
                                    'data-pjax' => '0',
                                    'data-method' => 'post',
                                    'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?'
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
