<?php

use dosamigos\multiselect\MultiSelect;
use yii\helpers\Html;

/* @var $field */
/* @var $chooseFields */
/* @var $additionalFields */
/* @var $characteristic */
//debug($additionalFields);
//dd($characteristic);
?>
<div class="row">
    <div class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" href="#required-field">* Основные поля</a>
                </h4>
            </div>
            <div id="required-field" class="panel-collapse collapse content in">
                <?php foreach ($chooseFields as $key => $value): ?>
                    <div class="row">
                        <div class="col-xs-6 panel panel-default">
                            <?php echo Yii::$app->getModule('import')->params['xml-main-fields'][$key] ?>
                        </div>
                        <div class="col-xs-6">
                            <?=
                            Html::dropDownList('requiredField[' . $key . ']', $field[$value] ?? $field['param'][$value], $field, ['class' => 'panel panel-default requiredField', 'prompt' => 'Укажите поле']);
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" href="#additional-field">Дополнительные поля</a>
                </h4>
            </div>
            <div id="additional-field" class="panel-collapse content collapse">
                <?php foreach ($additionalFields as $key => $value): ?>

                    <div class="row">
                        <div class="col-xs-6 panel panel-default">
                            <?= Yii::$app->params['xml-additional-fields'][$key] ?>
                        </div>
                        <div class="col-xs-6">
                            <?=
                            Html::dropDownList(
                                    'additionalField[' . $key . ']', $value != '' ? ($field[$value] ?? $field['param'][$value]) : 'Укажите поле', $field, ['class' => 'panel panel-default', 'prompt' => 'Укажите поле']);
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="row">
                    <div class="col-xs-6 panel panel-default">
                        Характеристики
                    </div>
                    <div class="col-xs-6">
                        <?=
                        MultiSelect::widget([
                            'id' => "characteristic-field",
                            "options" => ['multiple' => "multiple"], // for the actual multiselect
                            'data' => $field['param'], // data as array
                            'value' => $characteristic, // if preselected
                            'name' => 'characteristic[]', // name for the form
                            "clientOptions" =>
                            [
                                "includeSelectAllOption" => true,
                                'numberDisplayed' => 2
                            ],
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>