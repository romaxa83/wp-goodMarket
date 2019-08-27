<?php
    use yii\grid\GridView;
    use app\modules\users\roles\RolesAsset;
    use yii\helpers\Html;
    use common\controllers\AccessController;

    RolesAsset::register($this);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => [
            'id' => 'permissions-actions-table',
            'class' => 'table table-striped table-bordered table-hover'
        ],
        'columns' => [
            [
                'label' => 'Модуль',
                'attribute' => 'module',
            ],
            [
                'label' => 'Подмодуль',
                'attribute' => 'submodule',
            ],
            [
                'label' => 'Контроллер',
                'attribute' => 'controller',
            ],
            [
                'label' => 'Действие',
                'attribute' => 'action',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Управление',
                'headerOptions' => ['width' => '100'],
                'template' => '{delete}',
                'buttons' => [
                    // 'update' => function($url, $model, $index){
                    //     return Html::tag(
                    //         'a',
                    //         '',[
                    //         'title' => 'Редактировать роут',
                    //         'aria-label' => 'Редактировать роут',
                    //         'style' => 'color:rgb(63,140,187)',
                    //         'class' => 'grid-option fa fa-pencil edit-permission-action',
                    //         'data-index' => $index,
                    //         'data-pjax' => '1'
                    //     ]);
                    // },
                    'delete' => function($url, $model, $index){
                        return Html::tag(
                            'a',
                            '',[
                            'title' => 'Удалить роут',
                            'aria-label' => 'Удалить роут',
                            'style' => 'color:rgb(63,140,187)',
                            'class' => 'grid-option fa fa-trash delete-permission-action',
                            'data-index' => $index,
                            'data-pjax' => '1'
                        ]);
                    }
                ]
            ],
        ]
    ]);
?>
