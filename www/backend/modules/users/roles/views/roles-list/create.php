<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\users\roles\models\AuthItem */

$this->title = 'Создание роли';
$this->params['breadcrumbs'][] = ['label' => 'Список ролей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">

    <?= $this->render('_form', [
        'model' => $model,
        'permissions' => $permissions,
        'role_permissions' => []
    ]) ?>

</div>
