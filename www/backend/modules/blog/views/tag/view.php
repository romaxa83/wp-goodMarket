<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use backend\modules\blog\helpers\StatusHelper;
use backend\widgets\langwidget\LangWidget;
/* @var $this yii\web\View */
/* @var $tag backend\modules\blog\entities\Tag */
/* @var $access backend\modules\user\useCase\Access */

$this->title = $tag->manyLang[0]->title;
$this->params['breadcrumbs'][] = ['label' => 'Список тегов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-view">

    <p>
        <?= Html::a('Редактирование', ['update', 'id' => $tag->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Вернуться', Yii::$app->request->referrer, ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удаление', ['delete', 'id' => $tag->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены что хотите удалить этот тег?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-body">

                    <?= DetailView::widget([
                        'model' => $tag,
                        'attributes' => [
                            'id',
                            [
                                'attribute' => 'title',
                                'label' => 'Название',
                                'format' => 'raw',
                                'value' => function() use ($tag){
                                    $html = '<ul>';
                                    foreach($tag->manyLang as $keyLang => $oneLang) {
                                        $html .= '<li><b>' . $tag->aliasLang[$keyLang]->alias . '</b> : ' . $tag->alias . '</li>'; 
                                    }               
                                    return $html . '</ul>';
                                }
                            ],
                            [
                                'attribute' => 'alias',
                                'label' => 'Алиас',
                                'format' => 'raw',
                                'value' => $tag->alias,
                            ],
                            [
                                'attribute' => 'status',
                                'label' => 'Статус',
                                'format' => 'raw',
                                'value' => StatusHelper::label($tag->status),
                            ],
                        ],
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>

