<?php

use backend\modules\blog\helpers\DateHelper;
use backend\modules\blog\helpers\StatusHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use backend\modules\blog\helpers\ImageHelper;

/* @var $this yii\web\View */
/* @var $post backend\modules\blog\entities\Post */
/* @var $modificationsProvider yii\data\ActiveDataProvider */
/* @var $access backend\modules\user\useCase\Access */

$this->title = $post->title;
$this->params['breadcrumbs'][] = ['label' => 'Список постов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <p>
        <?php if($access->accessInView(Url::toRoute(['update']))):?>
            <?= Html::a('Редактировать', ['update', 'id' => $post->id], ['class' => 'btn btn-primary']) ?>
        <?php endif;?>
        <?= Html::a('Вернуться', Yii::$app->request->referrer, ['class' => 'btn btn-primary']) ?>
        <?php if($access->accessInView(Url::toRoute(['delete']))):?>
            <?= Html::a('Удалить', ['delete', 'id' => $post->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены ,что хотите удалить этот пост?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif;?>
    </p>
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-body">
                    <?= DetailView::widget([
                        'model' => $post,
                        'attributes' => [
                            'id',
                            [
                                'label' => 'Статус',
                                'format' =>'raw',
                                'value' => StatusHelper::label($post->status),
                            ],
                            [
                                'label' => 'Название поста',
                                'value' => $post->title,
                            ],
                            [
                                'label' => 'Алиас',
                                'value' => $post->alias,
                            ],
                            [
                                'label' => 'Категория',
                                'value' => ArrayHelper::getValue($post, 'category.title'),
                            ],
                            [
                                'label' => 'Привязаная страна',
                                'value' => ArrayHelper::getValue($post, 'country.name'),
                            ],
                            [
                                'label' => 'Теги',
                                'value' => implode(', ', ArrayHelper::getColumn($post->tags, 'title')),
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
                        'model' => $post,
                        'attributes' => [
                            [
                                'label' => 'Просмотры',
                                'value' => $post->views,
                            ],
                            [
                                'label' => 'Лайки',
                                'value' => $post->likes . ' (в разработке)',
                            ],
                            [
                                'label' => 'Ссылки',
                                'value' => $post->links . ' (в разработке)',
                            ],
                            [
                                'label' => 'Создана',
                                'value' => DateHelper::convertDateTime($post->created_at)
                            ],
                            [
                                'label' => 'Опубликована',
                                'value' => DateHelper::convertDateTime($post->published_at)
                            ],
                            [
                                'label' => 'Автор',
                                'value' => ArrayHelper::getValue($post, 'author.username')

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
                    <?php if($post->media_id !== null){
                        echo ImageHelper::renderImg($post->media->thumbs,'large');
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
                    <?= Yii::$app->formatter->asNtext($post->description) ?>
                </div>
            </div>
        </div>
    </div>


    <div class="box">
        <div class="box-header with-border">Контент</div>
        <div class="box-body">
            <?= Yii::$app->formatter->asHtml($post->content, [
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
                'model' => $post->getSeo(),
                'attributes' => [
                    [
                        'label' => 'H1',
                        'value' => $post->seo->h1,
                    ],
                    [
                        'label' => 'title',
                        'value' => $post->seo->title,
                    ],
                    [
                        'label' => 'keywords',
                        'value' => $post->seo->keywords,
                    ],
                    [
                        'label' => 'description',
                        'value' => $post->seo->description,
                    ],
                    [
                        'label' => 'seo_text',
                        'value' => $post->seo->seo_text,
                    ],
                ],
            ]) ?>
        </div>
    </div>

</div>
