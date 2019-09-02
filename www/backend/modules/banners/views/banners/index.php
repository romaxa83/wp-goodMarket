<?php

use app\modules\banners\BannersAsset;
use backend\widgets\hide_col\HideColWidget;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\controllers\AccessController;

$this->title = 'Баннера';
$this->params['breadcrumbs'][] = $this->title;

BannersAsset::register($this);
?>

<div class="box">
    <div class="box-header">
        <h3 class="box-title">Список баннеров</h3>
        <div class="pull-right">
            <a href="<?php echo Url::toRoute('create', TRUE); ?>" class="btn btn-primary" title="Добавить баннер">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
            </a>
            <?php
            echo HideColWidget::widget([
                'model' => 'banner',
                'hide_col' => $user_settings['hide-col'] ?? null,
                'attribute' => [
                    'title' => 'Название',
                    'text' => 'Текст',
                    'alias' => 'Ссылка',
                    'status' => 'Статус',
                ]
            ]);
            ?>
        </div>
    </div>
    <div class="box-body">
        <div class="table-responsive-md">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => [
                    'id' => 'banner-grid',
                    'class' => 'table table-striped table-bordered table-hover'
                ],
                'rowOptions' => function($model, $index, $widget, $grid) {
                    return ['id' => 'banner-row-' . $index];
                },
                'columns' => [
                    [
                        'attribute' => 'id',
                        'format' => 'html',
                        'value' => function($model) {
                            return '<i class="fa fa-arrows-alt"></i> ' . $model->id;
                        }
                    ],
                    [
                        'attribute' => 'title',
                        'format' => 'raw',
                        'value' => function($model) {
                            return $model->title;
                        },
                        'contentOptions' => [
                            'data-attr' => 'title',
                            'style' => $user_settings['hide-col'] !== null && in_array('title', $user_settings['hide-col']) ? 'display:none' : ''
                        ],
                        'headerOptions' => [
                            'data-attr' => 'title',
                            'style' => $user_settings['hide-col'] !== null && in_array('title', $user_settings['hide-col']) ? 'display:none' : ''
                        ],
                        'filterOptions' => [
                            'data-attr' => 'title',
                            'style' => $user_settings['hide-col'] !== null && in_array('title', $user_settings['hide-col']) ? 'display:none' : ''
                        ]
                    ],
                    [
                        'attribute' => 'text',
                        'format' => 'html',
                        'value' => function($model) {
                            return substr($model->text, 0, 100) . ' ....';
                        },
                        'contentOptions' => [
                            'data-attr' => 'text',
                            'style' => $user_settings['hide-col'] !== null && in_array('text', $user_settings['hide-col']) ? 'display:none' : ''
                        ],
                        'headerOptions' => [
                            'data-attr' => 'text',
                            'style' => $user_settings['hide-col'] !== null && in_array('text', $user_settings['hide-col']) ? 'display:none' : ''
                        ],
                        'filterOptions' => [
                            'data-attr' => 'text',
                            'style' => $user_settings['hide-col'] !== null && in_array('text', $user_settings['hide-col']) ? 'display:none' : ''
                        ]
                    ],
                    [
                        'attribute' => 'alias',
                        'format' => 'raw',
                        //'value' => 'tagsAsString'
                        'contentOptions' => [
                            'data-attr' => 'alias',
                            'style' => $user_settings['hide-col'] !== null && in_array('alias', $user_settings['hide-col']) ? 'display:none' : ''
                        ],
                        'headerOptions' => [
                            'data-attr' => 'alias',
                            'style' => $user_settings['hide-col'] !== null && in_array('alias', $user_settings['hide-col']) ? 'display:none' : ''
                        ],
                        'filterOptions' => [
                            'data-attr' => 'alias',
                            'style' => $user_settings['hide-col'] !== null && in_array('alias', $user_settings['hide-col']) ? 'display:none' : ''
                        ]
                    ],
                    [
                        'format' => 'raw',
                        'attribute' => 'status',
                        'value' => function($model, $key, $index, $column) {
                            $access = AccessController::isView(Yii::$app->controller, 'update-status');
                            $checked = ($model->publication == 1) ? 'true' : '';
                            $options = [
                                'id' => 'cd_' . $model->id,
                                'class' => 'tgl tgl-light publish-toggle status-toggle',
                                'data-id' => $model->id,
                                'data-url' => Url::to(['update-status']),
                                'disabled' => !$access
                            ];
                            return Html::beginTag('div') .
                                    Html::checkbox('status', $checked, $options) .
                                    Html::label('', 'cd_' . $model->id, ['class' => 'tgl-btn']) .
                                    Html::endTag('div');
                        },
                        'contentOptions' => [
                            'data-attr' => 'status',
                            'style' => $user_settings['hide-col'] !== null && in_array('status', $user_settings['hide-col']) ? 'display:none' : ''
                        ],
                        'headerOptions' => [
                            'data-attr' => 'status',
                            'style' => $user_settings['hide-col'] !== null && in_array('status', $user_settings['hide-col']) ? 'display:none' : ''
                        ],
                        'filterOptions' => [
                            'data-attr' => 'status',
                            'style' => $user_settings['hide-col'] !== null && in_array('status', $user_settings['hide-col']) ? 'display:none' : ''
                        ]
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Управление',
                        'headerOptions' => ['width' => '100'],
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'update' => function($url, $model, $index) {
                                $access = AccessController::isView(Yii::$app->controller, 'create-form');
                                if ($access) {
                                    $url = Url::to(['create-form', 'id' => $model->id]);
                                    return Html::tag(
                                                    'a', '', [
                                                'href' => $url,
                                                'title' => 'Редактировать баннер',
                                                'aria-label' => 'Редактировать баннер',
                                                'style' => 'color:rgb(63,140,187)',
                                                'class' => 'grid-option fa fa-pencil',
                                                'data-pjax' => '0'
                                    ]);
                                }
                            },
                            'delete' => function($url, $model, $index) {
                                $access = AccessController::isView(Yii::$app->controller, 'delete-banner');
                                if ($access) {
                                    $url = Url::to(['delete-banner', 'id' => $model->id]);
                                    return Html::tag(
                                                    'a', '', [
                                                'href' => $url,
                                                'title' => 'Удалить статью',
                                                'aria-label' => 'Удалить статью',
                                                'style' => 'color:rgb(63,140,187)',
                                                'class' => 'grid-option fa fa-trash',
                                                'data-confirm' => 'Вы уверены, что хотите  удалить этот элемент?',
                                                'data-method' => 'post',
                                                'data-pjax' => '0'
                                    ]);
                                }
                            }
                        ]
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>
