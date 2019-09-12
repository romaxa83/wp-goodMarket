<?php

use backend\modules\order\models\Order;
use yii\grid\GridView;
    use app\modules\order\OrderAsset;
    use yii\helpers\Html;

    OrderAsset::register($this);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => [
            'id' => 'order-products-table',
            'class' => 'table table-striped table-bordered table-hover'
        ],
        'columns' => [
            [
                'label' => 'Категория',
                'attribute' => 'category',
            ],
            [
                'label' => 'Продукт',
                'attribute' => 'product',
            ],
            [
                'label' => 'Вариация',
                'attribute' => 'variation',
            ],
            [
                'label' => 'Количество',
                'attribute' => 'count',
            ],
            [
                'label' => 'Наличие',
                'value' => function ($model) {
                    if (isset($model['amount']) && isset($model['count'])) {
                        return Order::getStatusBalance($model['amount'], $model['count']);
                    }
                    return '';
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Управление',
                'headerOptions' => ['width' => '100'],
                'template' => '{update} {delete} {view}',
                'buttons' => [
                    'update' => function($url, $model, $index)use($type){
                        if($type=='edit'){
                            return Html::tag(
                                'a',
                                '',[
                                'title' => 'Редактировать продукт',
                                'aria-label' => 'Редактировать продукт',
                                'style' => 'color:rgb(63,140,187)',
                                'class' => 'grid-option fa fa-pencil edit-order-product',
                                'data-index' => $model['product_id'] . '-' . $model['vproduct_id'],
                                'data-pjax' => '1'
                            ]);
                        }
                        return '';
                    },
                    'delete' => function($url, $model, $index)use($type){
                        if($type=='edit'){
                            return Html::tag(
                                'a',
                                '',[
                                'title' => 'Удалить продукт',
                                'aria-label' => 'Удалить продукт',
                                'style' => 'color:rgb(63,140,187)',
                                'class' => 'grid-option fa fa-trash delete-order-product',
                                'data-index' => $model['product_id'] . '-' . $model['vproduct_id'],
                                'data-pjax' => '1'
                            ]);
                        }
                    },
                    'view' => function($url, $model, $index){
                        return Html::tag(
                            'a',
                            '',[
                            'title' => 'Подробная информация о продукте',
                            'aria-label' => 'Подробная информация о продукте',
                            'style' => 'color:rgb(63,140,187)',
                            'class' => 'grid-option fa fa-eye show-order-product',
                            'data-index' => $model['product_id'] . '-' . $model['vproduct_id'],
                            'data-toggle' => \Yii::t('yii', 'modal'),
                            'data-target' => \Yii::t('yii', '#order-product'),
                            'data-pjax' => '1'
                        ]);
                    }
                ]
            ],
        ]
    ]);
?>
<div class="order-summ">
    <span>Сумма: <?=$order_summ?></span><br>
</div>
<div class="modal fade" id="order-product" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Информация о продукте</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
