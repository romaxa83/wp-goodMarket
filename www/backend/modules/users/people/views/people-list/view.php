<?php

use app\modules\users\people\PeopleAsset;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\controllers\AccessController;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $wishes common\models\Wishes */
/* @var $favorites common\models\Favorites */
/* @var $order backend\modules\order\models\Order */
/* @var $delivary_list */
/* @var $payment_list */

$this->title = $model->username !== null && $model->username !== '' ? $model->username : $model->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
PeopleAsset::register($this);
?>
<div class="user-view">
    <div class="row mb-15">
        <div class="col-xs-12">
            <?php
                if(AccessController::isView(Yii::$app->controller, 'index')){
                    echo Html::a('Вернуться', ['index', 'id' => $model->id], ['class' => 'btn btn-primary mr-15']);
                }  
            ?>
            <?php 
                if(AccessController::isView(Yii::$app->controller, 'update')){
                    echo Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary mr-15']);
                }
            ?>
        </div>
    </div>

    <div class="box-body">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#personal-data" data-toggle="tab" aria-expanded="true" style="color: #949ba2;">Данные пользователя</a>
            </li>
            <li class="">
                <a href="#orders" data-toggle="tab" aria-expanded="false" style="color: #949ba2;">Заказы</a>
            </li>
            <li class="">
                <a href="#favorites" data-toggle="tab" aria-expanded="false" style="color: #949ba2;">Избранное</a>
            </li>
            <li class="">
                <a href="#wishes" data-toggle="tab" aria-expanded="false" style="color: #949ba2;">Список желаний</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="personal-data">
                <div class="box-header with-border">
                    <h3 class="box-title">Данные пользователя</h3>
                </div>
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'first_name',
                        'last_name',
                        'phone',
                        //'auth_key',
                        //'password_hash',
                        //'password_reset_token',
                        'email:email',
                        //'status',
                        //'type',
                        //'created_at:datetime',
                        [
                            'attribute' => 'created_at',
                            'value'=>function($model){
                                return \Yii::$app->formatter->asDate($model->created_at,'php:Y-m-d H:i:s');
                            },
                        ],
                        //'updated_at',

                    ],
                ]) ?>
            </div>
            <div class="tab-pane fade" id="orders">
                <?php
                    if(AccessController::isView(Yii::$app->controller, 'orders-list')){
                        echo $this->render('orders-list',[
                            'orderDataProvider' => $orderDataProvider,
                            'delivary_list' => $delivary_list,
                            'payment_list' => $payment_list
                        ]);
                    }
                ?>
            </div>
            <div class="tab-pane fade" id="favorites">
                <?php
                    if(AccessController::isView(Yii::$app->controller, 'favorites-list')){
                        echo $this->render('favorites-list',[
                            'favorites' => $favorites
                        ]);
                    }
                ?>
            </div>
            <div class="tab-pane fade" id="wishes">
                <?php
                    if(AccessController::isView(Yii::$app->controller, 'wishes-list')){
                        echo $this->render('wishes-list',[
                            'wishes' => $wishes
                        ]);
                    }
                ?>
            </div>
        </div>
    </div>
</div>
