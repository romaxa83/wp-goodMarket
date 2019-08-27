<?php

use yii\helpers\Url;

$this->title = 'Редактировать блока "' . $model->title . '"';
$this->params['breadcrumbs'][] = ['label' => 'Список блоков', 'url' => Url::toRoute(['/settings/product-static-data/index'])];
$this->params['breadcrumbs'][] = $this->title;

$model->description = \backend\modules\settings\helpers\StaticDataHelper::parseDescriptionForAdmin($model->description);

echo $this->render('_form', [
    'model' => $model,
])

?>