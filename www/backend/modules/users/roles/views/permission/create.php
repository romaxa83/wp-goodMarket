<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\users\roles\models\AuthItem */

$this->title = 'Создание разрешения';
$this->params['breadcrumbs'][] = ['label' => 'Список разрешений', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">

    <?= $this->render('_form', [
        'model' => $model,
        'structure_elements' => $structure_elements,
        'dataProvider' => $dataProvider,
        'permission_routes' => $permission_routes
    ]) ?>

</div>
