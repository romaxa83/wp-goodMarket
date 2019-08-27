<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\users\roles\models\AuthItem */

$this->title = 'Редактирование разрешения:'.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Список разрешений', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="auth-item-update">

    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider,
        'permission_routes' => $permission_routes,
        'structure_elements' => $structure_elements
    ]) ?>

</div>
