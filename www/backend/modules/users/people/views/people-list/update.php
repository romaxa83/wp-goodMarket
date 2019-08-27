<?php

use yii\helpers\Html;
use app\modules\users\people\PeopleAsset;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $user->username;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $user->id, 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
PeopleAsset::register($this);
?>
<div class="user-update">

    <?= $this->render('_form', [
        'user' => $user
    ]) ?>

</div>
