<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$user = Yii::$app->user->identity;
$user_id = (!Yii::$app->user->isGuest)?$user->id:0;
?>
<div>
<?php $form = ActiveForm::begin(['id' => 'answer-form','fieldConfig' => ['enableLabel'=>false]]); ?>
        <?= $form->field($model, 'text')->textarea(['rows' => '6'])->error(false)?>
        <div class="input-group">
            <?= Html::tag(
                    'span',
                    'Ответить',[
                    'title' => 'Ответить',
                    'aria-label' => 'Ответить',
                    'class' => 'btn btn-main v3 pull-right add',
                    'data-action' => 'addAnswer',
                    'data-product_id' => $product_id,
                    'data-user_id' => $user_id,
                    'data-pjax' => '1'
                ]); ?>
        </div>
        
        <?php ActiveForm::end(); ?>
    </div>