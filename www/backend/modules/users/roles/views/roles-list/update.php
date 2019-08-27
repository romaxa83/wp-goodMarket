<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\users\roles\models\AuthItem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Список ролей', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'name' => $model->name]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="auth-item-update">
    <?= $this->render('_form', [
        'model' => $model,
        'permissions' => $permissions,
        'role_permissions' => $role_permissions
    ]) ?>

</div>
