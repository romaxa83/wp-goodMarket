<?php

use yii\helpers\Html;
use app\modules\users\people\PeopleAsset;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Создание ползьзователя';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
PeopleAsset::register($this);
?>
<div class="user-create">

    <?= $this->render('_form', [
        'user' => $user
    ]) ?>

</div>
