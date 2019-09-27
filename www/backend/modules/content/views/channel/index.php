<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Типы записей';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить тип записей', ['create'], ['class' => 'btn btn-success btn-sm invisible']) ?>
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
            <?= GridView::widget([
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
                    'created_at',
                    'updated_at',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header'=>'Действия',
                        'headerOptions' => ['width' => '80'],
                        'buttons' => [
                            'records' => function($url, $model) {
                                return Html::a(
                                    '<i class="fa fa-fw fa-list"></i>',
                                        ['/content/channel-record', 'channel_id' => $model->id],
                                        [
                                            'title' => 'Записи',
                                            'data-pjax' => '0',
                                        ]
                                );
                            },
                            'categories' => function($url, $model) {
                                return Html::a(
                                    '<i class="fa fa-tags"></i>',
                                    ['/content/channel-category', 'channel_id' => $model->id],
                                    [
                                        'title' => 'Категории',
                                        'data-pjax' => '0',
                                    ]
                                );
                            }
                        ],
                        'template' => '{update} {categories} {records} {delete}',
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

<?php
if(Yii::$app->session->hasFlash('errors')) {
    Yii::$app->session->getFlash('errors');
}
if(Yii::$app->session->hasFlash('success')) {
    Yii::$app->session->getFlash('success');
}