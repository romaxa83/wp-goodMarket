<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\users\people\PeopleAsset;
use backend\widgets\hide_col\HideColWidget;
use backend\modules\dispatch\components\CustomActionColumn;
use jino5577\daterangepicker\DateRangePicker;
use common\controllers\AccessController;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\users\people\models\PeopleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $user_settings */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
PeopleAsset::register($this);
?>
<div class="user-index">
    <div class="row mb-15">
        <div class="col-xs-12">

            <?php
                if(AccessController::isView(Yii::$app->controller, 'create')){
                    echo Html::a('Создать пользователя', ['create'], ['class' => 'btn btn-primary mr-15']);
                } 
            ?>
            <?= HideColWidget::widget([
                'model' => 'user',
                'hide_col' => $user_settings['hide-col'],
                'attribute' => [
                    'id' => 'ID',
                    'first_name' => 'Имя',
                    'last_name' => 'Фамилия',
                    'phone' => 'Телефон',
                    'email' => 'Email',
                    'create' => 'Время создания',
                    'action' => 'Управление'
                ]
            ])?>
        </div>
    </div>
    <div class="box">
<!--        <div class="box-header with-border">-->
<!--            <h3 class="box-title">Список пользователей</h3>-->
<!--            <div class="box-tools">-->
<!--                --><?php //echo $this->render('_search', ['model' => $searchModel]); ?>
<!--            </div>-->
<!--        </div>-->
        <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => [
                'class' => 'table table-hover'
            ],
            'columns' => [
//                [
//                    'class' => 'yii\grid\CheckboxColumn',
//                    'name' => 'delete',
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
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'id',
                    'value' => function($model){
                        return $model->id;
                    },
                    'contentOptions' => HideColWidget::setConfig('id',$user_settings['hide-col']),
                    'headerOptions' => HideColWidget::setConfig('id',$user_settings['hide-col']),
                    'filterOptions' => HideColWidget::setConfig('id',$user_settings['hide-col']),

                ],
                [
                    'attribute' => 'first_name',
                    'label' => 'Имя',
                    'format' => 'raw',
                    'value' => function($model){
                        return $model->first_name;
                    },
                    'contentOptions' => HideColWidget::setConfig('first_name',$user_settings['hide-col']),
                    'headerOptions' => HideColWidget::setConfig('first_name',$user_settings['hide-col']),
                    'filterOptions' => HideColWidget::setConfig('first_name',$user_settings['hide-col']),
                ],
                [
                    'attribute' => 'last_name',
                    'label' => 'Фамилия',
                    'format' => 'raw',
                    'value' => function($model){
                        return $model->last_name;
                    },
                    'contentOptions' => HideColWidget::setConfig('last_name',$user_settings['hide-col']),
                    'headerOptions' => HideColWidget::setConfig('last_name',$user_settings['hide-col']),
                    'filterOptions' => HideColWidget::setConfig('last_name',$user_settings['hide-col']),
                ],
                [
                    'attribute' => 'phone',
                    'label' => 'Телефон',
                    'format' => 'raw',
                    'value' => function($model){
                        return $model->phone;
                    },
                    'contentOptions' => HideColWidget::setConfig('phone',$user_settings['hide-col']),
                    'headerOptions' => HideColWidget::setConfig('phone',$user_settings['hide-col']),
                    'filterOptions' => HideColWidget::setConfig('phone',$user_settings['hide-col']),
                ],
                [
                    'attribute' => 'email',
                    'label' => 'Email',
                    'format' => 'raw',
                    'value' => function($model){
                        return $model->email;
                    },
                    'contentOptions' => HideColWidget::setConfig('email',$user_settings['hide-col']),
                    'headerOptions' => HideColWidget::setConfig('email',$user_settings['hide-col']),
                    'filterOptions' => HideColWidget::setConfig('email',$user_settings['hide-col']),
                ],
                [
                    'attribute' => 'created_at',
                    'value'=>function($model){
                        return \Yii::$app->formatter->asDate($model->created_at,'php:Y-m-d H:i:s');
                    },
                    'contentOptions'   => HideColWidget::setConfig('create',$user_settings['hide-col']),
                    'headerOptions' => HideColWidget::setConfig('create',$user_settings['hide-col']),
                    'filterOptions' => HideColWidget::setConfig('create',$user_settings['hide-col']),
                    'filter' => \kartik\date\DatePicker::widget([
                        'model' => $searchModel,
                        'attribute'=>'created_at',
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'autoclose' => true,
                        ]
                    ])
                ],
                [
                    'class' => CustomActionColumn::className(),
                    'option' => [
                        'url' => Url::to('/admin/users/people/people-list',true),
                        'name' => 'Сброс'
                    ],
                    'header' => 'Управление',
                    'contentOptions'   => HideColWidget::setConfig('action',$user_settings['hide-col']),
                    'headerOptions' => HideColWidget::setConfig('action',$user_settings['hide-col']),
                    'filterOptions' => HideColWidget::setConfig('action',$user_settings['hide-col']),
//                    'headerOptions' => [
//                        'width' => '140'
//                    ],
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            $access = AccessController::isView(Yii::$app->controller, 'view');
                            if($access){
                                return Html::a('<span class="fa fa-eye"></span>', ['/users/people/people-list/view', 'id' => $model->id], ['class' => 'grid-option', 'title' => 'Просмотр']);
                            }
                        },
                        'update' => function ($url, $model, $key) {
                            $access = AccessController::isView(Yii::$app->controller, 'update');
                            if($access){
                                return Html::a('<span class="fa fa-pencil"></span>', ['/users/people/people-list/update', 'id' => $model->id], ['class' => 'grid-option', 'title' => 'Редактировать пользователя']);
                            }
                        },
                        'delete' => function ($url, $model, $key) {
                            $access = AccessController::isView(Yii::$app->controller, 'delete');
                            if($access){
                                return Html::a('<span class="fa fa-trash"></span>', ['/users/people/people-list/delete', 'id' => $model->id], [
                                   'class' => 'grid-option',
                                   'title' => 'Удалить пользователя',
                                   'data' => [
                                   'confirm' => 'Вы действительно хотите удалить пользователя: '. $model->first_name .' '. $model->last_name .'. Всё связаные данные будут потеряны ',
                                   'method' => 'post',
                                   ],
                               ]);
                            }
                       },
//                        'user' => function ($url, $model, $key) {
//                            return Html::a('<span class="fa fa-user"></span>', ['/users/main/login', 'id' => $model->id], ['class' => 'grid-option', 'title' => 'Залогиниться']);
//                        },
//                        'ban' => function ($url, $model, $key) {
//                            if ($model->type == 1){
//                                return Html::a('<span class="fa fa-lock"></span>', ['/users/people/people-ban/create', 'id' => $model->id], [
//                                    'class' => 'grid-option',
//                                    'title' => 'Заблокировать пользователя'
//                                ]);
//                            }elseif ($model->type == 3){
//                                return Html::a('<span class="fa fa-unlock"></span>', ['/users/people/people-ban/delete', 'id' => $model->id], [
//                                    'class' => 'grid-option',
//                                    'title' => 'Разблокировать пользователя',
//                                    'data' => [
//                                        'confirm' => 'Вы действительно хотите разблокировать пользователя: '.$model->username,
//                                        'method' => 'post',
//                                    ],
//                                ]);
//                            }
//                        },
                    ],
                ]
            ]
        ]); ?>
</div>
