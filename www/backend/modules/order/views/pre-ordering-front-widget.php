<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
/* @var $guest \common\models\Guest */
?>

<div class="row row1 active">
    <div class="col-xs-12">
        <?php $form = ActiveForm::begin([
            'id' => 'ordering-guest',
            'action' => ['admin/order/order/guest-order'],
        ])?>
        <div class="wrap-checkout">
            <div class="form-group">
                <div class="input-group w-100 user-area flex-d flex-wrap">
                    <a href="#" class="no-btn" data-toggle="modal" data-target="#autorization-again">Постоянный
                        клиент</a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="form-group input-group cart-input w-100">
                        <?= $form->field($guest,'first_name')->textInput(['placeholder' => '*Ваше имя'])->label(false)?>
                    </div>
                    <div class="form-group input-group cart-input w-100">
                        <?= $form->field($guest,'last_name')->textInput(['placeholder' => '*Ваше фамилия'])->label(false)?>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="form-group input-group cart-input w-100">
                        <?= $form->field($guest,'email')->textInput(['placeholder' => '*Ваш email'])->label(false)?>
                    </div>
                    <div class="form-group input-group cart-input w-100">
                        <?= $form->field($guest, 'phone')->widget(MaskedInput::class, [
                            'name' => 'phone',
                            'mask' => '+38(999)-999-9999'
                        ])->textInput(['placeholder' => '*Ваш телефон'])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="form-group control-cart">
                <div class="input-group flex-d w-100">
                    <a href="#" class="btn btn-size-m btn-white flex-d align-items-center">Вернуться
                        назад</a>
                    <?= Html::submitButton('Дaлее',[
                        'class' => 'btn btn-size-m btn-main pull-right text-white flex-d align-items-cente guest-order-send',
                    ])?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end()?>
    </div>
</div>
