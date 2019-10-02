<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var $parentContainerId string */
/** @var $group string */
?>
<div class="add-block-widget" data-route="<?= Url::to(['block/add-block']) ?>">
    <input type="hidden" name="content-parent" value="<?= $parentContainerId ?>">
    <input type="hidden" name="content-group" value="<?= $group ?>">
    <?= Html::label('Добавить блок', 'additional-block-selector', array('class' => 'control-label')) ?>
    <div class="input-group">
        <?= Html::dropDownList(
            'block-type',
            'editor',
            Yii::$app->getModule('content')->params['blockTypes'],
            array(
                'prompt' => 'Выбор блока',
                'class' => 'form-control additional-block-selector',
            )
        ) ?>
        <div class="input-group-btn">
            <?= Html::button(
                'Добавить блок',
                [
                    'class' => 'btn btn-primary add-block',
                ]
            ) ?>
        </div>
        <!-- /btn-group -->
    </div>
</div>