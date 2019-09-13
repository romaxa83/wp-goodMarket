<?php

use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\widgets\hide_col\HideColWidget;
use common\controllers\AccessController;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Список магазинов</h3>
                <div class="pull-right">
                    <a href="<?php echo Url::toRoute('add-shop', TRUE); ?>" class="btn btn-primary" title="Добавить магазин">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    </a>
                    <?php
                    echo HideColWidget::widget([
                        'model' => 'product',
                        'hide_col' => $user_settings['hide-col'],
                        'attribute' => [
                            'name' => 'Название магазина',
                            'link' => 'Ссылка',
                            'date_update' => 'Дата обновления',
                            'prod_process' => 'Процесс'
                        ]
                    ]);
                    ?>
                </div>
            </div>
            <div class="box-body">
                <?php
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => [
                        'id' => 'import-table',
                        'class' => 'table table-striped table-bordered table-hover'
                    ],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'name',
                            'value' => function($model) {
                                return $model->name;
                            },
                            'contentOptions' => HideColWidget::setConfig('name', $user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('name', $user_settings['hide-col']),
                            'filterOptions' => HideColWidget::setConfig('name', $user_settings['hide-col']),
                        ],
                        [
                            'attribute' => 'link',
                            'value' => function($model) {
                                return $model->link;
                            },
                            'contentOptions' => HideColWidget::setConfig('link', $user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('link', $user_settings['hide-col']),
                            'filterOptions' => HideColWidget::setConfig('link', $user_settings['hide-col']),
                        ],
                        [
                            'attribute' => 'date_update',
                            'value' => function($model) {
                                return $model->date_update;
                            },
                            'contentOptions' => HideColWidget::setConfig('date_update', $user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('date_update', $user_settings['hide-col']),
                            'filterOptions' => HideColWidget::setConfig('date_update', $user_settings['hide-col']),
                        ],
                        [
                            'attribute' => 'prod_process',
                            'value' => function($model) {
                                switch ($model) {
                                    case ($model->prod_process == 1):
                                        return 'Добавление продуктов';
                                        break;
                                    case ($model->prod_process == 0):
                                        return 'Ожидается добавление продуктов';
                                        break;
                                    case ($model->edit_process == 1):
                                        return 'Редакторование продуктов';
                                        break;
                                    case ($model->update_process == 1 || $model->update_process == 3):
                                        return 'Обновление продуктов';
                                        break;
                                    default:
                                        return 'Нет';
                                        break;
                                }
                            },
                            'contentOptions' => HideColWidget::setConfig('prod_process', $user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('prod_process', $user_settings['hide-col']),
                            'filterOptions' => HideColWidget::setConfig('prod_process', $user_settings['hide-col']),
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => 'Управление',
                            'headerOptions' => ['width' => '100'],
                            'template' => '{edit} {delete} {update}',
                            'visibleButtons' => [
                                'delete' => function($model) {
                                    $access = AccessController::isView(Yii::$app->controller, 'delete');
                                    return $access;
                                }
                            ],
                            'buttons' => [
                                'edit' => function($url, $model, $index) {
                                    $access = AccessController::isView(Yii::$app->controller, 'edit');
                                    if ($access) {
                                        $url = Url::to(['import/edit', 'id' => $model->id]);
                                        return Html::tag(
                                                        'a', '', [
                                                    'href' => $url,
                                                    'title' => 'Редактировать магазин',
                                                    'aria-label' => 'Ркдактировать магазин',
                                                    'class' => 'glyphicon glyphicon-pencil',
                                                    'data-pjax' => '0'
                                        ]);
                                    }
                                },
                                'update' => function($url, $model, $index) {
                                    $access = AccessController::isView(Yii::$app->controller, 'update');
                                    if ($access) {
                                        $url = Url::to(['import/update', 'id' => $model->id]);
                                        return Html::tag(
                                                        'a', '', [
                                                    'href' => $url,
                                                    'title' => 'Обновить товары магазина',
                                                    'aria-label' => 'Обновить товары магазина',
                                                    'class' => 'glyphicon glyphicon-refresh',
                                                    'data-pjax' => '0'
                                        ]);
                                    }
                                },
                            ]
                        ],
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
