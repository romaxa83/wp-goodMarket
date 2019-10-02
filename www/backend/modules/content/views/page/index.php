<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Страницы';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    @media only screen and (max-width: 400px) {
        .btn.btn-success {
            width: 100%;
        }
        .box-header .box-tools {
            position: initial;
            width: 100%;
        }
    } 
</style>
<div class="box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить страницу', ['create'], ['class' => 'btn btn-success btn-sm invisible']) ?>
        <div class="box-tools pull-right btn-sm">
            <div class="has-feedback">
                <form id="search-form">
                    <input type="text" name="search" class="form-control input-sm" placeholder="Поиск страницы">
                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </form>
            </div>
        </div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    'title',
                    [
                        'attribute' => 'status',
                        'content' => function($data) {
                            return $data->status ? 'Включено' : 'Выключено';
                        }
                    ],
                    'creation_date',
                    'modification_date',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Действия',
                        'headerOptions' => ['width' => '80'],
                        'template' => '{update} {delete}',
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>

<?php
if (Yii::$app->session->hasFlash('errors')) {
    Yii::$app->session->getFlash('errors');
}
if (Yii::$app->session->hasFlash('success')) {
    Yii::$app->session->getFlash('success');
}