<?php

use backend\modules\blog\helpers\DateHelper;
use backend\modules\blog\helpers\StatusHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use backend\modules\blog\helpers\ImageHelper;
use backend\modules\blog\entities\TagLang;
/* @var $this yii\web\View */
/* @var $post backend\modules\blog\entities\Post */
/* @var $modificationsProvider yii\data\ActiveDataProvider */
/* @var $access backend\modules\user\useCase\Access */

$this->title = $model->manyLang[0]->title;
$this->params['breadcrumbs'][] = ['label' => 'Список постов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->manyLang[0]->title;
?>
<div class="user-view">

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Вернуться', Yii::$app->request->referrer, ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены ,что хотите удалить этот пост?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            [
                                'label' => 'Статус',
                                'format' =>'raw',
                                'value' => StatusHelper::label($model->status),
                            ],
                            [
                                'label' => 'Название поста',
                                'value' => function($model){
                                    return $model->manyLang[0]->title;
                                },
                            ],
                            [
                                'label' => 'Алиас',
                                'value' => $model->alias,
                            ],
                            [
                                'label' => 'Категория',
                                'value' => function($model){
                                    return $model->categoryTitle->title;
                                },
                            ],
                            [
                                'label' => 'Теги',
                                'value' => function($model){
                                    $tag = $model->tags;

                                    foreach($tag as $one){
                                        $tagID[] = $one->id;
                                    }

                                    return implode(ArrayHelper::getColumn(TagLang::find()->where(['in','tag_id',$tagID])->asArray()->andWhere(['lang_id' => 1])->all(),'title'),' , ');
                                }
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'label' => 'Просмотры',
                                'value' => $model->views,
                            ],
                            [
                                'label' => 'Лайки',
                                'value' => $model->likes . ' (в разработке)',
                            ],
                            [
                                'label' => 'Ссылки',
                                'value' => $model->links . ' (в разработке)',
                            ],
                            [
                                'label' => 'Создана',
                                'value' => DateHelper::convertDateTime($model->created_at)
                            ],
                            [
                                'label' => 'Опубликована',
                                'value' => DateHelper::convertDateTime($model->published_at)
                            ],
                            [
                                'label' => 'Автор',
                                'value' => ArrayHelper::getValue($model, 'author.username')

                            ]
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">Обложка</div>
                <div class="box-body">
                    <?php if($model->media_id !== null){
                        echo ImageHelper::renderImg($model->media->thumbs,'large');
                    } else {
                        echo ImageHelper::notImg();
                    } ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">Описание</div>
                <div class="box-body">
                    <?= Yii::$app->formatter->asHtml($model->manyLang[0]->description, [
                        'Attr.AllowedRel' => array('nofollow'),
                        'HTML.SafeObject' => true,
                        'Output.FlashCompat' => true,
                        'HTML.SafeIframe' => true,
                        'URI.SafeIframeRegexp'=>'%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
                    ]) ?>
                </div>
            </div>
        </div>
    </div>


    <div class="box">
        <div class="box-header with-border">Контент</div>
        <div class="box-body">
            <?= Yii::$app->formatter->asHtml($model->manyLang[0]->content, [
                'Attr.AllowedRel' => array('nofollow'),
                'HTML.SafeObject' => true,
                'Output.FlashCompat' => true,
                'HTML.SafeIframe' => true,
                'URI.SafeIframeRegexp'=>'%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
            ]) ?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">Seo</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model->getSeo(),
                'attributes' => [
                    [
                        'label' => 'H1',
                        'value' => $model->seo->h1,
                    ],
                    [
                        'label' => 'title',
                        'value' => $model->seo->title,
                    ],
                    [
                        'label' => 'keywords',
                        'value' => $model->seo->keywords,
                    ],
                    [
                        'label' => 'description',
                        'value' => $model->seo->description,
                    ],
                    [
                        'label' => 'seo_text',
                        'value' => $model->seo->seo_text,
                    ],
                ],
            ]) ?>
        </div>
    </div>

</div>
