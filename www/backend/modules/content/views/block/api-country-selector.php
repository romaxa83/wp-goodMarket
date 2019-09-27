<?php
/** @var int $id */
/** @var string $group */

use backend\modules\referenceBooks\models\Country;
use kartik\select2\Select2;

?>

<?php
$countries = Country::find()
    ->where(['status' => 1])
    ->indexBy('id')
    ->asArray()
    ->all();
?>

<?= Select2::widget([
    'name' => "{$group}[{$id}][text]",
    'data' => array_map(function($country) {
        return $country['name'];
    }, $countries),
    'options' => [
        'placeholder' => 'Выбор Страны из API',
        'class' => 'form-control',
        'id' => "{$group}-{$id}",
    ]
]) ?>