<?php

use app\modules\order\OrderAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use vova07\imperavi\Widget;
use kartik\select2\Select2;
use yii\web\JsExpression;

OrderAsset::register($this);
$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;

$status_list =  ['1'=>'Новый', '2'=>'Подтвержден', '3'=>'Отменен', '4'=>'Отправлен'];
$paid_list = ['0'=>'Не оплачен', '1'=>'Оплачен'];
$user_status_list = ['1'=>'Пользователь', '2'=>'Гость'];

if (isset($model->id)) {
    $action = 'edit?id=' . $model->id;
    $submit = 'Редактировать';
    if($model->delivary==2){
        $address_pieces = explode('|', $model->address);
        $model->street = $address_pieces[0];
        $model->home = $address_pieces[1];
        $model->flat = $address_pieces[2];
    }else{
        $address_pieces=null;
    }
    $status_disable = false;
    $user_status_disable = ($model->id!=null)?true:false;
    $model->user_status = ($model->user_id!=null)?1:2;
    $model->user_id = $model->user_id;
    $id = $model->id;
} else{
    $action = 'create';
    $submit = 'Сохранить';
    $model->delivary = 2;
    $status_disable = true;
    $user_status_disable = false;
    $address_pieces=null;
    $model->user_status = 2;
    $id=0;
}
if($model->status == 5){
    $status_list =  ['2'=>'Подтвержден', '3'=>'Отменен', '4'=>'Отправлен','5'=>'Заказ в один клик'];
}
?>
<?php $form = ActiveForm::begin([
                            'id' => 'form-order',
                            'method' => 'POST',
                            'enableAjaxValidation' => true,
                            'enableClientValidation' => false,
                            'validationUrl' => Url::toRoute(Yii::$app->controller->id . '/validation?id='.$id),
                            'validateOnType' => true,
                        ]);
?>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Заказ</a></li>
        <li><a href="#tab_2" data-toggle="tab" aria-expanded="false">Товары</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <?php echo $form->field($model, 'user_status')->dropDownList($user_status_list, ['disabled'=>$user_status_disable]); ?>
            <?php
            echo $form->field($model, 'user_id')->widget(Select2::classname(), [
                'name' => 'Order[user_id]',
                'data' => $userList,
                'value' => $model->user_id,
                'language' => 'eng',
                'disabled' => !$field_visible,
                'hideSearch' => true,
                'options' => ['placeholder' => 'Пользователь', 'class'=>'warehouse-select'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
            <div class="guest">
                <?php echo $form->field($guest, 'first_name')->textInput();?>
                <?php echo $form->field($guest, 'last_name')->textInput();?>
                <?php echo $form->field($guest, 'email')->textInput(); ?>
                <?php echo $form->field($guest, 'phone')->widget(yii\widgets\MaskedInput::class, [
                            'name' => 'phone',
                            'mask' => '+38(999)-999-9999'
                        ])->textInput(); ?>
            </div>
            <?php echo $form->field($model, 'status')->dropDownList($status_list, ['disabled'=>$status_disable]); ?>

            <?php echo $form->field($model, 'payment_method')->dropDownList($payment_method_list); ?>
            <?php echo $form->field($model, 'delivary')->dropDownList($delivery_list); ?>
            <?php $url = \yii\helpers\Url::to(['ajax-search-settlement-back']);
                echo $form->field($model, 'city')->widget(Select2::classname(), [
                        'initValueText' => $model->city,
                        'options' => ['placeholder' => 'Введите название населенного пункта', 'class'=>'settlement-select'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 1,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Загрузка результатов'; }"),
                            ],
                            'ajax' => [
                                'url' => $url,
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(city) { return city.text; }'),
                            'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                        ],
                    ]);
            ?>
            <div class="pickup <?=($model->delivary==1)?'':'pickup-hide'?>" data-required="<?=($model->delivary==1)?'true':'false'?>">
                <div class="form-group">
                    <?php
                        echo $form->field($model, 'address')->widget(Select2::classname(), [
                            'name' => 'Order[address]',
                            //'initValueText' => $model->address,
                            'language' => 'ru',
                            'disabled' => !$field_visible,
                            'hideSearch' => true,
                            'options' => ['placeholder' => 'Отделение', 'class'=>'warehouse-select'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?>
                </div>
            </div>
            <div class="address-delivary <?=($model->delivary==2)?'':'address-delivary-hide'?>" data-required="<?=($model->delivary==2)?'true':'false'?>">
               <?php echo $form->field($model, 'street')->textInput(['placeholder'=>'*Улица']); ?>
                <div class="row">
                    <div class="col-xs-6">
                        <?php echo $form->field($model, 'home')->textInput(['placeholder'=>'*Дом'])->label(false); ?>
                    </div>
                    <div class="col-xs-6">
                        <?php echo $form->field($model, 'flat')->textInput(['placeholder'=>'Квартира'])->label(false); ?>
                    </div>
                </div>
            </div>
            <?php echo $form->field($model, 'paid')->dropDownList($paid_list); ?>
            <?php
                echo $form->field($model, 'comment')->widget(Widget::className(), [
                    'settings' => [
                        'lang' => 'ru',
                        'minHeight' => 200,
                        'imageUpload' => Url::to(['image-upload']),
                        'imageDelete' => Url::to(['file-delete']),
                        'imageManagerJson' => Url::to(['images-get']),
                        'plugins' => [
                            'clips',
                            'fullscreen',
                        ],
                    ],
                    'plugins' => [
                        'imagemanager' => 'vova07\imperavi\bundles\ImageManagerAsset',
                    ],
                ]);
            ?>
            <div class="form-group">
                <label class="control-label">Сумма</label>
                <input type="text" class="form-control" name="order_summ" value="<?=$order_summ?>" readonly>
            </div>
        </div>
        <div class="tab-pane" id="tab_2">
            <?php echo $this->render('order-products-form',$order_products_params)?>
            <div class="products-table">
                <?php echo $this->render('products-table',$order_products_params)?>
            </div>
        </div>
   </div>
</div>
<div class="form-group">
    <?php echo Html::submitButton('Сохранить и выйти в список', ['class' => 'btn btn-primary save-order', 'name' => 'save', 'value' => '/order/order']) ?>
    <?php echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary save-order', 'name' => 'save', 'value' => $action]) ?>
    <a href="<?php echo Url::to(['/order/order'])?>" class="btn btn-danger">Отмена</a>
</div>
<?php ActiveForm::end(); ?>
