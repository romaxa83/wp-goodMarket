<?php

use app\modules\settings\SettingsAsset;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\controllers\AccessController;

/* @array $payment ArrayDataProvider */
/* @array $delivery ArrayDataProvider */

$this->title = 'Настройки';
$this->params['breadcrumbs'][] = $this->title;
SettingsAsset::register($this);
?>
<div class="app-default-index">
    <div class="row">
        <div class="col-md-4">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Настройка языка</h3>
                    <?php
                        if(AccessController::isView(\Yii::$app->controller, 'add-row-lang')){
                            echo Html::tag(
                                'a', '', [
                                'href' => '#',
                                'title' => 'Добавить запись',
                                'style' => ['color' => 'rgb(63,140,187)', 'float' => 'right'],
                                'class' => 'grid-option fa fa-plus add-lang',
                                'data-action' => 'add',
                                'data-pjax' => '1'
                            ]);
                        }
                    ?>
                </div>
                <div class="box-body">
                    <?=
                    GridView::widget(['dataProvider' => $dataProvider,
                        'tableOptions' => [
                            'id' => 'lang',
                            'class' => 'table table-bordered'
                        ],
                        'rowOptions' => function($model, $key, $index, $grid) {
                            return [
                                'background-color' => 'white',
                                'data-key' => $key,
                                'data-index' => $index
                            ];
                        },
                        'showFooter' => false,
                        'summary' => '',
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'name',
                                'format' => 'text',
                                'label' => 'Язык'
                            ],
                            [
                                'attribute' => 'alias',
                                'format' => 'text',
                                'label' => 'Алиас'
                            ],
                            [
                                'attribute' => 'currency',
                                'format' => 'text',
                                'label' => 'Валюта'
                            ],
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'label' => 'Статус',
                                'value' => function($model, $key, $index, $column) use ($defaultLanguage) {
                                    $access = AccessController::isView(Yii::$app->controller, 'update-status');
                                    $checked = ($model['status'] == 1) ? 'true' : '';
                                    $options = [
                                        'id' => 'cd_' . $key,
                                        'class' => 'tgl tgl-light publish-toggle status-toggle',
                                        'data-id' => $key,
                                        'data-url' => Url::to(['update-status']),
                                        'disabled' => ($model['alias'] == $defaultLanguage || !$access) ? TRUE : FALSE
                                    ];

                                    return Html::beginTag('div') .
                                            Html::checkbox('status', $checked, $options) .
                                            Html::label('', 'cd_' . $key, ['class' => 'tgl-btn']) .
                                            Html::endTag('div');
                                }
                            ],
                            [
                                'class' => yii\grid\ActionColumn::class,
                                'template' => '{update} {delete}',
                                'header' => 'Управление',
                                'headerOptions' => ['width' => '100'],
                                'buttons' => [
                                    'update' => function($url, $model, $key) use ($defaultLanguage) {
                                        $access = AccessController::isView(Yii::$app->controller, 'update-row-lang');
                                        if($access){
                                            return Html::tag(
                                                    'a', '', [
                                                    'href' => '#',
                                                    'title' => 'Редактировать запись',
                                                    'aria-label' => 'Редактировать запись',
                                                    'style' => 'color:rgb(63,140,187)',
                                                    'class' => ($model['alias'] == $defaultLanguage) ? 'grid-option fa fa-pencil' : 'grid-option fa fa-pencil edit-lang',
                                                    'data-action' => 'update',
                                                    'data-key' => $key,
                                                    'data-pjax' => '1',
                                                    'disabled' => ($model['alias'] == $defaultLanguage) ? TRUE : FALSE
                                            ]);
                                        }
                                    },
                                    'delete' => function($url, $model, $key) use ($defaultLanguage) {
                                        $access = AccessController::isView(Yii::$app->controller, 'delete-row-lang');
                                        if($access){
                                            return Html::tag(
                                                    'a', '', [
                                                    'href' => '#',
                                                    'title' => 'Удалить запись',
                                                    'aria-label' => 'Удалить запись',
                                                    'style' => 'color:rgb(63,140,187)',
                                                    'class' => ($model['alias'] == $defaultLanguage) ? 'grid-option fa fa-trash' : 'grid-option fa fa-trash delete-lang',
                                                    'data-confirm' => ($model['alias'] != $defaultLanguage) ? 'Вы уверены, что хотите удалить этот элемент?' : FALSE,
                                                    'data-key' => $key,
                                                    'data-pjax' => '1',
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
        <div class="col-md-4">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Настройка контактов</h3>
                </div>
                <div class="box-body">
                    <?=
                    GridView::widget([
                        'dataProvider' => $contact,
                        'tableOptions' => [
                            'id' => 'contact',
                            'class' => 'table table-bordered update-setting'
                        ],
                        'rowOptions' => function($model, $key, $index, $grid) {
                            return [
                                'background-color' => 'white'
                            ];
                        },
                        'showFooter' => false,
                        'columns' => [
                            [
                                'contentOptions' => function($model){
                                    return ['class' => 'text-data','data-oldValue' => $model['body']];
                                },
                                'label' => 'Контакты',
                                'format' => 'raw',
                                'value' => function($model) {
                                    return $model['body'];
                                }
                            ],
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'label' => 'Статус',
                                'value' => function($model, $key, $index, $column){
                                    $access = AccessController::isView(Yii::$app->controller, 'update-status-social');
                                    $checked = ($model['status'] == 1) ? 'true' : '';
                                    $options = [
                                        'id' => 'contact_status_' . $key,
                                        'class' => 'tgl tgl-light status-toggle',
                                        'data-id' => $model['id'],
                                        'data-url' => Url::to(['update-status-contact']),
                                        'disabled' => !$access
                                    ];

                                    return Html::beginTag('div') .
                                            Html::checkbox('status', $checked, $options) .
                                            Html::label('', 'contact_status_' . $key, ['class' => 'tgl-btn']) .
                                            Html::endTag('div');
                                }
                            ],
                        ],
                    ]);
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="lat">Широта</label>
                                <input type="number" id="lat" class="form-control coordinate" name="lat" value="<?php echo (isset($coordinate['lat'])) ? $coordinate['lat'] : FALSE; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="lng">Долгота</label>
                                <input type="number" id="lng" class="form-control coordinate" name="lng" value="<?php echo (isset($coordinate['lng'])) ? $coordinate['lng'] : FALSE; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Настройка социальных групп</h3>
                </div>
                <div class="box-body">
                    <?=
                    GridView::widget([
                        'dataProvider' => $group,
                        'tableOptions' => [
                            'id' => 'group',
                            'class' => 'table table-bordered update-setting'
                        ],
                        'rowOptions' => function($model, $key, $index, $grid) {
                            return [
                                'background-color' => 'white',
                                'data-key' => $key,
                                'data-index' => $index,
                                'data-id' => $model['id'],
                                'data-type' => 'social-group',
                            ];
                        },
                        'showFooter' => false,
                        'columns' => [
                            [
                                'contentOptions' => ['width' => '1%'],
                                'label' => 'Группа',
                                'format' => 'raw',
                                'value' => function($model) {
                                    return $model['name'];
                                }
                            ],
                            [
                                'contentOptions' => function($model){
                                    return ['class' => 'text-data','data-oldValue' => $model['link']];
                                },
                                'label' => 'Значение',
                                'format' => 'raw',
                                'value' => function($model) {
                                    if(strlen($model['link']) > 30){
                                        $value = substr($model['link'],0,30) . '...';
                                    }
                                    return $value ?? $model['link'];
                                }
                            ],
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'label' => 'Статус',
                                'value' => function($model, $key, $index, $column){
                                    $access = AccessController::isView(Yii::$app->controller, 'update-status-social');
                                    $checked = ($model['status'] == 1) ? 'true' : '';
                                    $options = [
                                        'id' => 'social_status_' . $key,
                                        'class' => 'tgl tgl-light publish-toggle status-toggle',
                                        'data-id' => $key,
                                        'data-url' => Url::to(['update-status-social']),
                                        'disabled' => (!$access || empty($model['link']))
                                    ];

                                    return Html::beginTag('div') .
                                            Html::checkbox('status', $checked, $options) .
                                            Html::label('', 'social_status_' . $key, ['class' => 'tgl-btn']) .
                                            Html::endTag('div');
                                }
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Настройка оплаты</h3>
                    <?php
                        if(AccessController::isView(\Yii::$app->controller, 'add-row')){
                            echo Html::tag(
                                    'a', '', [
                                    'href' => '#',
                                    'title' => 'Добавить запись',
                                    'style' => ['color' => 'rgb(63,140,187)', 'float' => 'right'],
                                    'class' => 'grid-option fa fa-plus add-payment new-setting',
                                    'data-action' => 'add',
                                    'data-type' => 'payment',
                                    'data-pjax' => '1'
                                ]);
                        }
                    ?>
                </div>
                <div class="box-body">
                    <?=
                    GridView::widget(['dataProvider' => $payment,
                        'tableOptions' => [
                            'id' => 'payment',
                            'class' => 'table table-bordered update-setting'
                        ],
                        'rowOptions' => function($model, $key, $index, $grid) {
                            return [
                                'background-color' => 'white',
                                'data-key' => $key,
                                'data-index' => $index,
                                'data-id' => $model['id'],
                                'data-type' => 'payment',
                            ];
                        },
                        'showFooter' => false,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'contentOptions' => function($model){
                                    return ['class' => 'text-data','data-oldValue' => $model['name']];
                                },
                                'attribute' => 'name',
                                'format' => 'text',
                                'label' => 'Способ оплаты',
                            ],
                            [
                                'attribute' => 'position',
                                'format' => 'text',
                                'label' => 'Позиция',
                                'contentOptions' => ['class' => 'text-data number']
                            ],
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'label' => 'Статус',
                                'value' => function($model, $key, $index, $column) use ($defaultLanguage) {
                                    $access = AccessController::isView(Yii::$app->controller, 'update-status-payment');
                                    $checked = ($model['status'] == 1) ? 'true' : '';
                                    $options = [
                                        'id' => 'cd_payment_' . $key,
                                        'class' => 'tgl tgl-light publish-toggle status-toggle',
                                        'data-id' => $key,
                                        'data-url' => Url::to(['update-status-payment']),
                                        'disabled' => !$access
                                    ];

                                    return Html::beginTag('div') .
                                            Html::checkbox('status', $checked, $options) .
                                            Html::label('', 'cd_payment_' . $key, ['class' => 'tgl-btn']) .
                                            Html::endTag('div');
                                }
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Настройка доставки</h3>
                    <?php
                        if(AccessController::isView(\Yii::$app->controller, 'add-row')){
                            echo Html::tag(
                                'a', '', [
                                'href' => '#',
                                'title' => 'Добавить запись',
                                'style' => ['color' => 'rgb(63,140,187)', 'float' => 'right'],
                                'class' => 'grid-option fa fa-plus add-delivery new-setting',
                                'data-action' => 'add',
                                'data-type' => 'delivery',
                                'data-pjax' => '1'
                            ]);
                        }
                    ?>
                </div>
                <div class="box-body">
                    <?=
                    GridView::widget(['dataProvider' => $delivery,
                        'tableOptions' => [
                            'id' => 'delivery',
                            'class' => 'table table-bordered update-setting'
                        ],
                        'rowOptions' => function($model, $key, $index, $grid) {
                            return [
                                'background-color' => 'white',
                                'data-key' => $key,
                                'data-index' => $index,
                                'data-id' => $model['id'],
                                'data-type' => 'delivery',
                            ];
                        },
                        'showFooter' => false,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'contentOptions' => function($model){
                                    return ['class' => 'text-data','data-oldValue' => $model['name']];
                                },
                                'attribute' => 'name',
                                'format' => 'text',
                                'label' => 'Способ доставки',
                            ],
                            [
                                'attribute' => 'position',
                                'format' => 'text',
                                'label' => 'Позиция',
                                'contentOptions' => ['class' => 'text-data number']
                            ],
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'label' => 'Статус',
                                'value' => function($model, $key, $index, $column) use ($defaultLanguage) {
                                    $access = AccessController::isView(Yii::$app->controller, 'update-status-payment');
                                    $checked = ($model['status'] == 1) ? 'true' : '';
                                    $options = [
                                        'id' => 'cd_delivery_' . $key,
                                        'class' => 'tgl tgl-light publish-toggle status-toggle',
                                        'data-id' => $key,
                                        'data-url' => Url::to(['update-status-delivery']),
                                        'disabled' => !$access
                                    ];

                                    return Html::beginTag('div') .
                                            Html::checkbox('status', $checked, $options) .
                                            Html::label('', 'cd_delivery_' . $key, ['class' => 'tgl-btn']) .
                                            Html::endTag('div');
                                }
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
