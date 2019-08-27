<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
  'action' => ['index'],
  'method' => 'get',
]); ?>
<div class="input-group input-group-sm search-box">
    <div class="input-group-btn">
        <?= Html::submitButton('<i class="fa fa-search"></i>', ['class' => 'btn btn-default']) ?>
    </div>

    <?= $form->field($model, 'search')->textInput(['class' => 'form-control pull-right input-sm','placeholder' => 'Поиск'])->label(false) ?>

    <div class="input-group-btn">
        <?= Html::button('Очистить',['class' => 'btn btn-success clear_search','data-location' => '/users/administrators/administrators/index'])?>
    </div>
</div>
<?php ActiveForm::end(); ?>
