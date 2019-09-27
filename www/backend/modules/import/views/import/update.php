<?php

/* @var $model backend\modules\exportxml\models\ParseShop */
/* @var $fields */
/* @var $chooseFields */
/* @var $additionalFields */
/* @var $categories */
/* @var $chooseCategory */
/* @var $characteristic */

$this->title = 'Редактирование магазин';
$this->params['breadcrumbs'][] = ['label' => 'Список магазинов', \yii\helpers\Url::toRoute(['/exportxml/export/index'])];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="article-update">

    <?= $this->render('_form', [
        'shop' => $model,
        'fields' => $fields,
        'chooseFields' => $chooseFields,
        'additionalFields' => $additionalFields,
        'categories' => $categories,
        'chooseCategory' => $chooseCategory,
        'characteristic' => $characteristic
    ]) ?>

</div>