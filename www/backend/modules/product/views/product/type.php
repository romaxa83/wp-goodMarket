<?php

use kartik\color\ColorInput;
use yii\bootstrap\ActiveForm;
?>
<?php echo ActiveForm::begin()->field($product_characteristic, 'value', ['inputTemplate' => '<div style="display: flex">{input}<button class=" pull-right btn btn-default save-characteristic" title="Добавить характеристику"><i class="fa fa fa-floppy-o" aria-hidden="true"></i></button></div>'])->widget(ColorInput::classname(), ['options' => ['placeholder' => 'Select color ...', 'readonly' => 'readonly']]); ?>
<?php //echo $form->field($product_characteristic, 'value', ['inputTemplate' => '<div class="form-group"><div style="display: flex;">{input}<button class=" pull-right btn btn-default save-characteristic" title="Добавить характеристику"><i class="fa fa fa-floppy-o" aria-hidden="true"></i></button></div></div>']); ?>
