<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\users\roles\RolesAsset;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список ролей';
$this->params['breadcrumbs'][] = $this->title;
RolesAsset::register($this);
?>
<div class="auth-item-index">
    <div class="row mb-15">
        <div class="col-xs-12">
            <?= Html::a('Создать роль', ['create'], ['class' => 'btn btn-primary mr-15']) ?>
            <?= Html::a('Удалить несколько', ['delete'], ['class' => 'btn btn-danger all_delete_assignment']) ?>
        </div>
    </div>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Список ролей</h3>
        </div>
        <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => [
                'class' => 'table table-hover'
            ],
            'columns' => [
                ['class' => 'yii\grid\CheckboxColumn',
                    'name' => 'delete',
                    'cssClass'=>'mass-checked',
                    'checkboxOptions'=>function ($model, $key, $index){
                        return [
                            'class' => 'custom-checkbox'
                        ];
                    },
                    'contentOptions' => function($model, $key, $index, $column) {
                        return [
                            'class' => 'element-check check-del',
                        ];
                    },
                    'header' => Html::checkBox(
                        'delete_all',
                        false,
                        [
                            'id' => 'selectAllElements',
                            'type' => 'checkbox',
                            'class' => 'custom-checkbox select-on-check-all'
                        ]
                    ),
                    'headerOptions' => [
                        'width' => '34',
                        'class' => 'select-all-header'
                    ]
                ],
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'name',
                    'format' => 'text',
                    'label' => 'Имя роли',
                ],
                'description:text',
                'created_at:datetime',
                [
                    'attribute' => 'role',
                    'format' => 'raw',
                    'label' => 'Статус',
                    // 'value' => function ($dataProvider) {
                    //     $role = $dataProvider;
                    //     if (count($role->authAssignments) > 0) {
                    //         return Html::a('Снять роль', ['user-revoke', 'name' => $role->name], ['data-value' => $role->authAssignments, 'class' => 'btn btn-default']);
                    //     }else{
                    //         return '-';
                    //     }
                    // }
                    'value' => function($model){
                        $checked = count($model->authAssignments) > 0 ? 'true' : '';
                        $disabled = count($model->authAssignments) === 0 ? 'true' : '';
                        $options = [
                            'id' => 'cp_'.$model->name,
                            'class' => 'tgl tgl-light publish-toggle status-toggle',
                            'data-id' => $model->name,
                            'data-url' => \yii\helpers\Url::to(['user-revoke?name=' . $model->name])
                        ];

                        if ($disabled) {
                            $options['disabled'] = $disabled;
                        }

                        return Html::beginTag('div').
                            Html::checkbox('status', $checked, $options).
                            Html::label('', 'cp_'.$model->name, ['class' => 'tgl-btn']).
                            Html::endTag('div');
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header'=>'Управление',
                    'headerOptions' => [
                        'width' => '110'
                    ],
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            return Html::a('<span class="fa fa-eye"></span>', ['/users/roles/roles-list/view', 'name' => $model->name], [
                                'title' => 'Посмотреть',
                                'class' => 'grid-option'
                            ]);
                        },
                        'update' => function ($url, $model, $key) {
                            return Html::a('<span class="fa fa-pencil"></span>', ['/users/roles/roles-list/update', 'name' => $model->name], [
                                'title' => 'Редактировать роль',
                                'class' => 'grid-option'
                            ]);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<span class="fa fa-trash"></span>', ['/users/roles/roles-list/delete', 'name' => $model->name], [
                                'title' => 'Удалить',
                                'class' => 'grid-option',
                                'data' => [
                                    'confirm' => 'Вы действительно хотите удалить этот элемент?',
                                    'method' => 'post',]
                            ]);
                        },
                    ],
                ],
            ],
        ]); ?>
        </div>
    </div>
</div>
