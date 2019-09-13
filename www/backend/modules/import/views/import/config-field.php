<?php

use dosamigos\multiselect\MultiSelect;
use yii\helpers\Html;

/* @var $field */
/* @var $chooseFields */
/* @var $additionalFields */
/* @var $characteristic */
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
                <?php foreach (Yii::$app->getModule('import')->params['xml-main-fields'] as $key => $name): ?>
                    <div class="row">
                        <div class="col-xs-6 panel panel-default shop-field">
                            <?= $name ?>
                        </div>
                        <?php $class = 'panel panel-default requiredField import-field ' . (((\Yii::$app->controller->action->id == 'load-edit-settings') && ($key == 'id' || $key == 'vendor_code')) ? 'not-edit' : '') ?>
                        <div class="col-xs-6">
                            <?=
                            Html::dropDownList(
                                    'requiredField[' . $key . ']', (!is_null($chooseFields)) ? ($chooseFields[$key] ?? $chooseFields['param'][$key]) : 'Укажите поле', $field, ['class' => $class, 'prompt' => 'Укажите поле', 'readonly' => true]);
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
                <?php foreach (Yii::$app->getModule('import')->params['xml-additional-fields'] as $key => $name): ?>

                    <div class="row">
                        <div class="col-xs-6 panel panel-default shop-field">
                            <?= $name ?>
                        </div>
                        <div class="col-xs-6">
                            <?=
                            Html::dropDownList(
                                    'additionalField[' . $key . ']', (!is_null($chooseFields)) ? ($chooseFields[$key] ?? $chooseFields['param'][$key]) : 'Укажите поле', $field, ['class' => 'panel panel-default import-field', 'prompt' => 'Укажите поле']);
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="row">
                    <div class="col-xs-6 panel panel-default shop-field">
                        Характеристики
                    </div>
                    <div class="col-xs-6">
                        <select id="characteristic-field" class="panel panel-default import-field" name="characteristic[]" multiple="multiple">
                            <?php foreach ($field['param'] as $key => $value): ?>
                                <option value="<?= $key ?>"><?= $value ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>