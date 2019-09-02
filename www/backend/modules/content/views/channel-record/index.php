<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var backend\modules\content\models\Channel $channel */

$this->title = $channel->title;
$this->params['breadcrumbs'][] = ['label' => 'Типы записей', 'url' => ['/content/channel']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить запись', ['create', 'channel_id' => $channel->id], ['class' => 'btn btn-success btn-sm']) ?>
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
                        'template' => '{update} {delete}',
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

<?php
$js = <<< JS
$('.has-feedback #search-form').on('keypress', function(e) {
    if(e.which == 13) {
        $('#search-form').submit();
    }
})
JS;

$this->registerJs($js, VIEW::POS_END);