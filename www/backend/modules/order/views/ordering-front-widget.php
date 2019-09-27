<?php
use yii\helpers\Url;

/* @var $deliveries */
/* @var $buyer */
/* @var $payments */

?>

<div class="row row1 active">
    <div class="col-xs-12">
        <div class="wrap-checkout">
            <div class="form-group">
                <div class="input-group w-100 user-area-info flex-d flex-wrap justify-content-between">
                    <span class="user-data"
                          data-user-status="<?= $buyer?>"
                          data-user-id="<?=$user->id?>">
                        <strong><?=$user->first_name?> <?=$user->last_name?></strong>, <?=$user->phone?>
                    </span>
                    <button type="button" class="no-btn btn-decor">Редактировать</button>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <div class="input-group self-select w-100">
                            <select id="delivery" class="delivery form-control" name="delivery">
                                <option value="0">*Способ доставки</option>
                                <?php if(isset($deliveries) && !empty($deliveries)):?>
                                    <?php foreach ($deliveries as $id => $delivery):?>
                                        <option value="<?= $id?>"><?= $delivery?></option>
                                    <?php endforeach;?>
                                <?php endif?>
                            </select>
                            <div class="help-block" style="color: red"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="pickup input-group self-select w-100">
                            <select class="settlement-select form-control" name="settle">
                            </select>
                            <div class="help-block" style="color: red"></div>
                        </div>
                    </div>
                    <div class="courier-delivery">
                        <div class="form-group input-group cart-input w-100">
                            <input type="text" class="street form-control" placeholder="*Улица"/>
                            <div class="help-block" style="color: red"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group input-group cart-input w-100">
                                    <input type="text" class="house form-control" placeholder="*Дом"/>
                                    <div class="help-block" style="color: red"></div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group input-group cart-input w-100">
                                    <input type="text" class="flat form-control" placeholder="Кв"/>
                                    <div class="help-block" style="color: red"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="np-delivery pickup">
                        <select class="warehouse-select form-control" name="warehouse">
                        </select>
                        <div class="help-block" style="color: red"></div>
                    </div>
                    <div class="form-group input-group cart-input w-100">
                        <?= 
                            yii\widgets\MaskedInput::widget([
                                'name' => 'phone',
                                'mask' => '+38(999) - 999 - 9999',
                                'options' => [
                                    'class' => 'form-control',
                                    'id' => 'orderPhone'
                                ],
                                'value' => $user->phone
                            ]);
                        ?>
                        <div class="help-block" style="color: red"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <div class="input-group self-select w-100">
                            <select class="payment form-control">
                                <option value="0">*Способ оплаты</option>
                                <?php if(isset($payments) && !empty($payments)):?>
                                    <?php foreach ($payments as $id => $payment):?>
                                        <option value="<?= $id?>"><?= $payment?></option>
                                    <?php endforeach;?>
                                <?php endif?>
                            </select>
                            <div class="help-block" style="color: red"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group text-area w-100">
                            <textarea class="comment form-control" placeholder="Комментарий к заказу"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group control-cart">
                <div class="input-group flex-d w-100">
                    <a href="<?= Url::to(Yii::$app->request->referrer)?>" class="btn btn-size-m btn-white flex-d align-items-center">Вернуться
                        назад</a>
                    <button class="send-order btn btn-size-m btn-main pull-right text-white flex-d align-items-center">
                        Оформить заказ
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>