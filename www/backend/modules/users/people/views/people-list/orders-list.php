<?php
use common\helpers\ProductsHelper;
use backend\modules\order\helpers\AddressHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $orders */
/* @var $delivary_list */
/* @var $payment_list */

$status_list =  ['1'=>'Новый', '2'=>'Подтвержден', '3'=>'Отменен', '4'=>'Отправлен'];
$paid_list = ['1'=>'Оплачен', '0'=>'Не оплачен'];
?>
<div class="box-header with-border">
    <h3 class="box-title">Список заказов</h3>
</div>

<div class="order-table">
    <?php
        echo GridView::widget([
            'dataProvider' => $orderDataProvider,
            //'filterModel' => $searchModel,
            'tableOptions' => [
                            'class' => 'table table-striped table-bordered table-hover'
                        ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'address',
                    'label' => 'Адрес доставки',
                    'format' => 'raw',
                    'value' => function($model){
                        return ($model['city'] !== null && $model['address'] !== null) ?
                        AddressHelper::niceWithCity($model['city'],$model['address']):
                        '<ul><li>Заказ в один клик</li><li>'.$model['phone'].'</li></ul>';
                    },
                ],
                [
                    'attribute' => 'delivary',
                    'label' => 'Способ доставки',
                    'value' => function($model)use($delivary_list){
                        return $delivary_list[$model['delivary']];
                    },
                ],
                [
                    'attribute' => 'status',
                    'label' => 'Статус заказа',
                    'value' => function($model)use($status_list){
                        return $status_list[$model['status']];
                    },
                ],
                [
                    'attribute' => 'payment_method',
                    'label' => 'Способ оплаты',
                    'value' => function($model) use($payment_list){
                        return $payment_list[$model['payment_method']];
                    },
                ],
                [
                    'attribute' => 'paid',
                    'label' => 'Статус оплаты',
                    'value' => function($model) use($paid_list){
                        return $paid_list[$model['paid']];
                    },
                ],
                [
                    'attribute' => 'cost',
                    'label' => 'Сумма по заказу',
                    'value' => function($model){
                        return $model['cost'];
                    },
                ],
                [
                    'attribute' => 'date',
                    'label' => 'Дата офрмления',
                    'value' => function($model){
                        return $model['date'];
                    },
                ],
            ]
        ]);
    ?>
</div>

