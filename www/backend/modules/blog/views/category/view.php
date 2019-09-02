<?php

use backend\modules\blog\entities\Category;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use backend\modules\blog\helpers\StatusHelper;

/* @var $this yii\web\View */
/* @var $category backend\modules\blog\entities\Category */
/* @var $access backend\modules\user\useCase\Access */

$this->title = $category->title;
$this->params['breadcrumbs'][] = ['label' => 'Список категорий', 'url' => ['index']];
foreach ($category->parents as $parent){
    if(!$parent->isRoot()){
        $this->params['breadcrumbs'][] = ['label' => $parent->title,'url' => ['view','id' => $parent->id]];
    }
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-view">

    <p>
        <?= Html::a('Редактирование', ['update', 'id' => $category->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Вернуться', Yii::$app->request->referrer, ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удаление', ['delete', 'id' => $category->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены что хотите удалить эту категорию?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-body">

                    <?= DetailView::widget([
                        'model' => $category,
                        'attributes' => [
                            'id',
                            [
                                'attribute' => 'title',
                                'label' => 'Название',
                                'format' => 'raw',
                                'value' => $category->title,
                            ],
                            [
                                'attribute' => 'alias',
                                'label' => 'Алиас',
                                'format' => 'raw',
                                'value' => $category->alias,
                            ],
                            [
                                'attribute' => 'status',
                                'label' => 'Статус',
                                'format' => 'raw',
                                'value' => StatusHelper::label($category->status),
                            ],
                        ],
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>
