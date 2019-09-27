<?php

use kartik\color\ColorInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;

?>


'<label class="control-label">Значение характеристики</label>';
<?php
echo ColorInput::widget([
    'name' => 'color_33',
    'value' => 'red',
    'showDefaultPalette' => false,
    'options' => ['placeholder' => 'Choose your color ...'],
    'pluginOptions' => [
        'showInput' => true,
        'showInitial' => true,
        'showPalette' => true,
        'showPaletteOnly' => true,
        'showSelectionPalette' => true,
        'showAlpha' => false,
        'allowEmpty' => false,
        'preferredFormat' => 'name',
        'palette' => [
            [
                "white", "black", "grey", "silver", "gold", "brown",
            ],
            [
                "red", "orange", "yellow", "indigo", "maroon", "pink"
            ],
            [
                "blue", "green", "violet", "cyan", "magenta", "purple",
            ],
        ]
    ]
]);
?>


