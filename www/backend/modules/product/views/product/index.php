<?php

use backend\modules\product\ProductAsset;
use backend\widgets\hide_col\HideColWidget;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use backend\modules\product\models\CustomActionColumn;
use common\controllers\AccessController;

ProductAsset::register($this);
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Список продуктов</h3>
                <div class="pull-right">
                    <a href="<?php echo Url::toRoute('create', TRUE); ?>" class="btn btn-primary" title="Добавить продукт">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    </a>
                    <?php
                    echo HideColWidget::widget([
                        'model' => 'product',
                        'hide_col' => $user_settings['hide-col'],
                        'attribute' => [
                            'id' => 'ID',
                            'category' => 'Категория',
                            'name' => 'Название',
                            'price' => 'Цена товара',
                            'sale' => 'Скидка(%)',
                            'sale_price' => 'Цена со скидкой',
                            'rating' => 'Рейтинг',
                            'remainder' => 'Остаток на складе',
                            'status' => 'Статус',
                            'action' => 'Управление'
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
                        'id' => 'product-table',
                        'class' => 'table table-striped table-bordered table-hover'
                    ],
                    'columns' => [
                        [
                            'attribute' => 'id',
                            'value' => function($model) {
                                return $model['id'];
                            },
                            'contentOptions' => HideColWidget::setConfig('id', $user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('id', $user_settings['hide-col'], ['width' => '50']),
                            'filterOptions' => HideColWidget::setConfig('id', $user_settings['hide-col']),
                        ],
                        [
                            'attribute' => 'category_id',
                            'value' => function($model) {
                                return $model->category['name'];
                            },
                            'contentOptions' => HideColWidget::setConfig('category', $user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('category', $user_settings['hide-col'], ['width' => '110']),
                            'filterOptions' => HideColWidget::setConfig('category', $user_settings['hide-col']),
                        ],
                        [
                            'label' => 'Название',
                            'attribute' => 'product_lang_name',
                            'value' => function($model) {
                                return $model->productLang[0]->name;
                            },
                            'contentOptions' => HideColWidget::setConfig('name', $user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('name', $user_settings['hide-col']),
                            'filterOptions' => HideColWidget::setConfig('name', $user_settings['hide-col']),
                        ],
                        [
                            'label' => 'Цена',
                            'attribute' => 'product_lang_price',
                            'value' => function($model) {
                                return number_format($model->productLang[0]['price'], 2, '.', '');
                            },
                            'contentOptions' => HideColWidget::setConfig('price', $user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('price', $user_settings['hide-col']),
                            'filterOptions' => HideColWidget::setConfig('price', $user_settings['hide-col']),
                        ],
                        [
                            'label' => 'Скидка(%)',
                            'attribute' => 'sale',
                            'value' => function($model) {
                                return number_format(0, 2, '.', '');
                            },
                            'contentOptions' => HideColWidget::setConfig('sale', $user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('sale', $user_settings['hide-col']),
                            'filterOptions' => HideColWidget::setConfig('sale', $user_settings['hide-col']),
                        ],
                        [
                            'label' => 'Цена со скидкой',
                            'attribute' => 'sale_price',
                            'value' => function($model) {
                                return number_format(0, 2, '.', '');
                            },
                            'contentOptions' => HideColWidget::setConfig('sale_price', $user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('sale_price', $user_settings['hide-col']),
                            'filterOptions' => HideColWidget::setConfig('sale_price', $user_settings['hide-col']),
                        ],
                        [
                            'attribute' => 'rating',
                            'value' => function($model) {
                                return $model->rating;
                            },
                            'contentOptions' => HideColWidget::setConfig('rating', $user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('rating', $user_settings['hide-col']),
                            'filterOptions' => HideColWidget::setConfig('rating', $user_settings['hide-col']),
                        ],
                        [
                            'attribute' => 'amount',
                            'value' => function($model) {
                                return $model->amount;
                            },
                            'contentOptions' => HideColWidget::setConfig('remainder', $user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('remainder', $user_settings['hide-col']),
                            'filterOptions' => HideColWidget::setConfig('remainder', $user_settings['hide-col']),
                        ],
                        [
                            'format' => 'raw',
                            'attribute' => 'stock_publish',
                            'filter' => [0 => 'Выкл.', 1 => 'Вкл.'],
                            'value' => function($model) {
                                return (($model->stock_publish == 1) ? '<div style="color: #00a65a;">Вкл.</div>' : '<div style="color: #dd4b39;">Выкл.</div>');
                            },
                            'contentOptions' => HideColWidget::setConfig('sklad', $user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('sklad', $user_settings['hide-col'], ['width' => '90']),
                            'filterOptions' => HideColWidget::setConfig('sklad', $user_settings['hide-col']),
                        ],
                        [
                            'format' => 'raw',
                            'attribute' => 'publish',
                            'filter' => [0 => 'Выкл.', 1 => 'Вкл.'],
                            'value' => function($model) {
                                $access = AccessController::isView(Yii::$app->controller, 'update-status');
                                $checked = (isset($model->publish) && $model->publish == 1) ? TRUE : FALSE;
                                $options = [
                                    'id' => 'cd_' . $model->id,
                                    'class' => 'tgl tgl-light publish-toggle status-toggle',
                                    'data-id' => $model->id,
                                    'data-url' => Url::to(['update-status']),
                                    'disabled' => ($model->stock_publish == 0) ? TRUE : FALSE
                                ];
                                return Html::beginTag('div') .
                                        Html::checkbox('status', $checked, $options) .
                                        Html::label('', 'cd_' . $model->id, ['class' => 'tgl-btn']) .
                                        Html::endTag('div');
                            },
                            'contentOptions' => HideColWidget::setConfig('status', $user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('status', $user_settings['hide-col'], ['width' => '1']),
                            'filterOptions' => HideColWidget::setConfig('status', $user_settings['hide-col']),
                        ],
                        [
                            'class' => CustomActionColumn::className(),
                            'header' => '',
                            'contentOptions' => HideColWidget::setConfig('action', $user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('action', $user_settings['hide-col'], ['width' => '1']),
                            'filterOptions' => HideColWidget::setConfig('action', $user_settings['hide-col']),
                            'template' => '{update}',
                            'filter' => '<a href="' . Url::to(['/product/product'], TRUE) . '"><i class="grid-option fa fa-filter" title="Сбросить фильтр"></i></a>',
                            'buttons' => [
                                'update' => function($url, $model, $index) {
                                    $access = AccessController::isView(Yii::$app->controller, 'update');
                                    if ($access) {
                                        return Html::tag('a', '', [
                                                    'href' => '/admin/product/product/update?id=' . $model['id'],
                                                    'title' => 'Редактировать',
                                                    'aria-label' => 'Редактировать',
                                                    'class' => 'grid-option fa fa-pencil'
                                        ]);
                                    }
                                }
                            ]
                        ],
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
</div>

