<?php

use backend\modules\order\helpers\TotalCostHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\order\OrderAsset;
use backend\widgets\hide_col\HideColWidget;
use backend\modules\order\helpers\AddressHelper;
use backend\modules\order\components\CustomActionColumn;
use common\controllers\AccessController;

/* @var $user_settings backend\modules\order\controllers\OrderController */
/* @var $delivery_list backend\modules\order\controllers\OrderController */
/* @var $payment_list backend\modules\order\controllers\OrderController */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;

OrderAsset::register($this);

$status_list =  ['1'=>'Новый', '2'=>'Подтвержден', '3'=>'Отменен', '4'=>'Отправлен', '5'=>'Заказ в один клик'];
$paid_list = ['1'=>'Оплачен', '0'=>'Не оплачен'];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="row mb-15">
            <div class="col-xs-6">
                <?php if(AccessController::isView(Yii::$app->controller, 'create')){
                        echo Html::a('Добавить заказ', ['create'], ['class' => 'btn btn-primary mr-15']);
                    }
                ?>
                <?= HideColWidget::widget([
                    'model' => 'order',
                    'hide_col' => $user_settings['hide-col'],
                    'attribute' => [
                        'id' => 'ID',
                        'name' => 'Имя пользователя',
                        'status' => 'Статус заказа',
                        'total-cost' => 'Сумма заказа',
                        'delivery' => 'Способ доставки',
                        'payment' => 'Способ оплаты',
                        'address' => 'Адресс доставки',
                        'paid' => 'Оплачен',
                        'date' => 'Дата',
                        'action' => 'Управление'
                    ]
                ])?>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Заказы</h3>
            </div>
            <div class="box-body">
                <?php
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => [
                        'id' => 'order-table',
                        'class' => 'table table-striped table-bordered table-hover'
                    ],
                    'columns' => [
                        [
                            'attribute' => 'id',
                            'contentOptions' => HideColWidget::setConfig('id',$user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('id',$user_settings['hide-col']),
                            'filterOptions' => HideColWidget::setConfig('id',$user_settings['hide-col']),
                        ],
                        [
                            'attribute' => 'fullname',
                            'format' => 'raw',
                            'value' => function($model){
                                if($model->user_id !== null){
                                    return $model->user->getFullName();
                                } elseif ($model->guest_id !== null){
                                    return $model->guest->getFullName();
                                } else {
                                    return 'Неизвестно';
                                }
                            },
                            'contentOptions' => HideColWidget::setConfig('name',$user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('name',$user_settings['hide-col']),
                            'filterOptions' => HideColWidget::setConfig('name',$user_settings['hide-col']),
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => function($model)use($status_list){
                                $access = AccessController::isView(\Yii::$app->controller, 'change-attribut');
                                if($access){
                                    if($model->status != 5){
                                        unset($status_list[5]);
                                    }
                                    return Html::dropDownList('statusOrder',$model->status,$status_list,['class'=>'form-control attribut-order attribut-status','data-field' => 'status']);
                                }
                            },
                            'contentOptions' => HideColWidget::setConfig('status',$user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('status',$user_settings['hide-col']),
                            'filterOptions' => HideColWidget::setConfig('status',$user_settings['hide-col']),
                            'filter' => $status_list
                        ],
                        [
                            'attribute' => 'История статусов',
                            'format' => 'raw',
                            'value' => function($model)use($status_list){
                                $status = '';
                                if(!empty($model->getStatusHistory())){
                                    $history = '<ul class="tooltip-history-order">';
                                    foreach ($model->getStatusHistory() as $oneStatus){
                                        $history .= '<li>Cтатус : ' . $status_list[$oneStatus['status']] . ' | Дата : ' . $oneStatus['date'] . '</li>';
                                    }
                                    $history .= '</ul>';
                                    $status = '<div class="show-history">Показать историю' . $history . '</div>';
                                }
                                if($model->status != 5){
                                    unset($status_list[5]);
                                }
                                return $status;
                            },
                            'contentOptions' => HideColWidget::setConfig('statusHistory',$user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('statusHistory',$user_settings['hide-col']),
                            'filterOptions' => HideColWidget::setConfig('statusHistory',$user_settings['hide-col']),
                            'filter' => $status_list
                        ],
                        [
                            'label' => 'Сумма заказа',
                            'format' => 'raw',
                            'value' => function($model){
                                return TotalCostHelper::getTotalCost($model->id);
                            },
                            'contentOptions' => HideColWidget::setConfig('total-cost',$user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('total-cost',$user_settings['hide-col']),
                            'filterOptions' => HideColWidget::setConfig('total-cost',$user_settings['hide-col']),
                        ],
                        [
                            'attribute' => 'delivary',
                            'format' => 'raw',
                            'value'=>function($model) use ($delivery_list){
                                return $delivery_list[$model->delivary];
                            },
                            'contentOptions' => HideColWidget::setConfig('delivery',$user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('delivery',$user_settings['hide-col']),
                            'filterOptions' => HideColWidget::setConfig('delivery',$user_settings['hide-col']),
                            'filter' => $delivery_list
                        ],
                        [
                            'label' => 'Способ и статус оплаты',
                            'attribute' => 'payment_method',
                            'format' => 'raw',
                            'value'=>function($model)use($payment_list,$paid_list){
                                $access = AccessController::isView(\Yii::$app->controller, 'change-attribut');
                                if($access){
                                    $label = ($model->paid == $model::UNPAID) ? 'red' : 'green';
                                    if($model->payment_method != 0){
                                        unset($payment_list[0]);
                                    }
                                    return  Html::dropDownList('paymentMethodOrder',$model->payment_method,$payment_list,['class'=>'form-control attribut-order','data-field' => 'payment_method']) . '<br/>'. Html::dropDownList('paymentMethodOrder',$model->paid,$paid_list,['class'=>'form-control attribut-order','data-field' => 'paid']);
                                }

                            },
                            'contentOptions' => HideColWidget::setConfig('payment',$user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('payment',$user_settings['hide-col']),
                            'filterOptions' => HideColWidget::setConfig('payment',$user_settings['hide-col']),
                            'filter' => $payment_list
                        ],
                        [
                            'attribute' => 'address',
                            'format' => 'raw',
                            'value' => function($model){
                                if($model->city !== null && $model->address !== null){
                                    return AddressHelper::niceWithCity($model->city,$model->address,$model->phone);
                                }
                                return '<ul><li>' . $model->phone . '</li></ul>';
                            },
                            'contentOptions' => HideColWidget::setConfig('address',$user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('address',$user_settings['hide-col']),
                            'filterOptions' => HideColWidget::setConfig('address',$user_settings['hide-col']),
                        ],
                        [
                            'attribute' => 'date',
                            'format' => 'raw',
                            'contentOptions' => HideColWidget::setConfig('date',$user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('date',$user_settings['hide-col']),
                            'filterOptions' => HideColWidget::setConfig('date',$user_settings['hide-col']),
                            'filter' => \kartik\date\DatePicker::widget([
                                'model' => $searchModel,
                                'attribute' => 'date',
                                'pluginOptions' => [
                                    'format' => 'yyyy-mm-dd',
                                    'autoclose' => true,
                                ]
                            ])
                        ],
                        [
                            'class' => CustomActionColumn::className(),
                            'option' => [
                                'url' => Url::to('/admin/order/order/index',true),
                                'name' => 'Сброс'
                            ],
                            'header'=>'Управление',
                            'contentOptions' => HideColWidget::setConfig('action',$user_settings['hide-col']),
                            'headerOptions' => HideColWidget::setConfig('action',$user_settings['hide-col']),
                            'filterOptions' => HideColWidget::setConfig('action',$user_settings['hide-col']),
//                            'headerOptions' => ['width' => '100'],
                            'template' => '{update} {delete} {products}',
                            'buttons' => [
                                'update' => function($url, $model, $index) {
                                    $access = AccessController::isView(\Yii::$app->controller, 'edit');
                                    if($access){
                                        $url = Url::to(['edit?id=' . $model->id]);
                                        return Html::tag(
                                            'a',
                                            '',[
                                            'href' => $url,
                                            'title' => 'Редактировать заказ',
                                            'aria-label' => 'Редактировать заказ',
                                            'style' => 'color:rgb(63,140,187)',
                                            'class' => 'grid-option fa fa-pencil',
                                            'data-pjax' => '0'
                                        ]);
                                    }
                                },
                                'delete' => function($url, $model, $index){
                                    $access = AccessController::isView(\Yii::$app->controller, 'delete');
                                    if($access){
                                        $url = Url::to(['delete','id' => $model->id]);
                                        return Html::tag(
                                            'a',
                                            '',[
                                            'href' => $url,
                                            'title' => 'Удалить заказ',
                                            'aria-label' => 'Удалить статью',
                                            'style' => 'color:rgb(63,140,187)',
                                            'class' => 'grid-option fa fa-trash',
                                            'data-confirm' =>'Вы уверены, что хотите  удалить этот элемент?',
                                            'data-method' => 'post',
                                            'data-pjax' => '0'
                                        ]);
                                    }
                                },
                                'products' => function($url, $model, $index){
                                    $access = AccessController::isView(\Yii::$app->controller, 'show-order-products');
                                    if($access){
                                        $url = Url::to(['show-order-products?id='.$model->id]);
                                        return Html::tag(
                                            'a',
                                            '',[
                                            'href' => $url,
                                            'title' => 'Перейти к продуктам заказа',
                                            'aria-label' => 'Перейти к продуктам заказа',
                                            'style' => 'color:rgb(63,140,187)',
                                            'class' => 'grid-option fa fa-product-hunt',
                                            'data-method' => 'post',
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

