<?php

use backend\modules\blog\helpers\ImageHelper;
use backend\modules\blog\widgets\settings\SettingsWidget;
use kartik\date\DatePicker;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\blog\BlogAsset;
use backend\modules\blog\entities\Post;
use backend\modules\blog\helpers\DateHelper;
use backend\modules\blog\helpers\StatusHelper;
use backend\modules\blog\components\CustomActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\blog\forms\search\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $user_settings */
/* @var $page */
/* @var $access backend\modules\user\useCase\Access */

$this->title = 'Список постов';
$this->params['breadcrumbs'][] = $this->title;

BlogAsset::register($this);
?>
<div class="post-index">

    <div class="row mb-15">
        <div class="col-xs-6">
            <?= Html::a('Создать пост', Url::toRoute(['create']), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Список постов</h3>
        </div>
        <div class="box-body table-flexible">
            <?= GridView::widget([
                'id' => 'blog-posts',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => [
                    'class' => 'table table-hover'
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'id',
                        'label' => 'ID',
                        'format' => 'raw',
                        'value' => function(Post $model){
                            return $model->id;
                        },
                        'contentOptions' => SettingsWidget::setConfig('id',$user_settings['hide-col']??null),
                        'headerOptions' => SettingsWidget::setConfig('id',$user_settings['hide-col']??null),
                        'filterOptions' => SettingsWidget::setConfig('id',$user_settings['hide-col']??null,['width' => '50']),
                    ],
                    [
                        'attribute' => 'media_id',
                        'label' => 'Обложка',
                        'format' => 'raw',
                        'value' => function(Post $model){
                            return ($model->media_id !== null)
                                ?ImageHelper::renderImg($model->media->thumbs,'small')
                                :ImageHelper::notImg();
                        },
                        'contentOptions' => SettingsWidget::setConfig('media_id',$user_settings['hide-col']??null),
                        'headerOptions' => SettingsWidget::setConfig('media_id',$user_settings['hide-col']??null),
                        'filterOptions' => SettingsWidget::setConfig('media_id',$user_settings['hide-col']??null),
                    ],
                    [
                        'attribute' => 'title',
                        'label' => 'Название',
                        'format' => 'raw',
                        'value' => function(Post $model){
                            return Html::a(Html::encode($model->title), ['view', 'id' => $model->id]);
                        },
//                       'options' => ['style' => 'width:50px;max-width:50px'],
                        'contentOptions' => SettingsWidget::setConfig('title',$user_settings['hide-col']??null),
                        'headerOptions' => SettingsWidget::setConfig('title',$user_settings['hide-col']??null),
                        'filterOptions' => SettingsWidget::setConfig('title',$user_settings['hide-col']??null),
                    ],
                    [
                        'attribute' => 'alias',
                        'label' => 'Алиас',
                        'format' => 'raw',
                        'value' => function(Post $model){
                            return $model->alias;
                        },
                        'contentOptions' => SettingsWidget::setConfig('alias',$user_settings['hide-col']??null),
                        'headerOptions' => SettingsWidget::setConfig('alias',$user_settings['hide-col']??null),
                        'filterOptions' => SettingsWidget::setConfig('alias',$user_settings['hide-col']??null),
                    ],
                    [
                        'attribute' => 'category_id',
                        'label' => 'Категория',
                        'format' => 'raw',
                        'value' => function(Post $model){
                            return $model->category->title;
                        },
                        'contentOptions' => SettingsWidget::setConfig('category_id',$user_settings['hide-col']??null),
                        'headerOptions' => SettingsWidget::setConfig('category_id',$user_settings['hide-col']??null),
                        'filterOptions' => SettingsWidget::setConfig('category_id',$user_settings['hide-col']??null),
                    ],
                    [
                        'attribute' => 'description',
                        'label' => 'Описание',
                        'format' => 'raw',
                        'value' => function(Post $model){
                            return StringHelper::truncateWords(strip_tags($model->description),5);
                        },
                        'contentOptions' => SettingsWidget::setConfig('description',$user_settings['hide-col']??null),
                        'headerOptions' => SettingsWidget::setConfig('description',$user_settings['hide-col']??null),
                        'filterOptions' => SettingsWidget::setConfig('description',$user_settings['hide-col']??null),
                    ],
                    [
                        'attribute' => 'views',
                        'label' => 'Просмотров',
                        'format' => 'raw',
                        'value' => function(Post $model){
                            return $model->views;
                        },
                        'contentOptions' => SettingsWidget::setConfig('views',$user_settings['hide-col']??null),
                        'headerOptions' => SettingsWidget::setConfig('views',$user_settings['hide-col']??null),
                        'filterOptions' => SettingsWidget::setConfig('views',$user_settings['hide-col']??null),
                    ],
                    [
                        'attribute' => 'likes',
                        'label' => 'Лайков',
                        'format' => 'raw',
                        'value' => function(Post $model){
                            return $model->likes;
                        },
                        'contentOptions' => SettingsWidget::setConfig('likes',$user_settings['hide-col']??null),
                        'headerOptions' => SettingsWidget::setConfig('likes',$user_settings['hide-col']??null),
                        'filterOptions' => SettingsWidget::setConfig('likes',$user_settings['hide-col']??null),
                    ],
                    [
                        'attribute' => 'links',
                        'label' => 'Ссылки',
                        'format' => 'raw',
                        'value' => function(Post $model){
                            return $model->likes;
                        },
                        'contentOptions' => SettingsWidget::setConfig('links',$user_settings['hide-col']??null),
                        'headerOptions' => SettingsWidget::setConfig('links',$user_settings['hide-col']??null),
                        'filterOptions' => SettingsWidget::setConfig('links',$user_settings['hide-col']??null),
                    ],
                    [
                        'label' => 'Публикация',
                        'format' => 'raw',
                        'value' => function(Post $model){
                            return DateHelper::postStatus($model);
                        },
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'language' => 'ru',
                            'type' =>DatePicker::TYPE_COMPONENT_APPEND,
                            'attribute' => 'created_at',
                            'options' => ['placeholder' => 'Дата создания'],
                            'convertFormat' => true,
                            'pluginOptions' => [
                                'orientation' => 'bottom',
                                'autoUpdateInput' => false,
                                'format' => 'dd-MM-yyyy',
                                'autoclose'=>true,
                                'weekStart'=>1, //неделя начинается с понедельника
                            ]
                        ]),
                        'contentOptions' => SettingsWidget::setConfig('published',$user_settings['hide-col']??null),
                        'headerOptions' => SettingsWidget::setConfig('published',$user_settings['hide-col']??null),
                        'filterOptions' => SettingsWidget::setConfig('published',$user_settings['hide-col']??null),
                    ],
                    [
                        'attribute' => 'status',
                        'label' => 'Статус',
                        'format' => 'raw',
                        'value' => function(Post $model){
                            return StatusHelper::checkBox($model,'/blog/post/status-change');
                        },
                        'filter' => StatusHelper::listPost(),
                        'contentOptions' => SettingsWidget::setConfig('status',$user_settings['hide-col']??null),
                        'headerOptions' => SettingsWidget::setConfig('status',$user_settings['hide-col']??null),
                        'filterOptions' => SettingsWidget::setConfig('status',$user_settings['hide-col']??null,['class' => 'minw-160px']),
                    ],
                    [
                        'attribute' => 'is_main',
                        'label' => 'Пост на гл.',
                        'format' => 'raw',
                        'value' => function(Post $model){
                            $position_html = '';
                            if ($model->is_main == 1){
                                $position_html .= Html::dropDownList(
                                    'position',
                                    $model->position,
                                    $model->getArrayPosition(),
                                    [
                                        'class' => 'position_in_main',
                                        'data-url' => \yii\helpers\Url::to(['/blog/post/set-position']),
                                        'data-post-id' => $model->id
                                    ]
                                );
                            }
                            return StatusHelper::checkBox($model,'/blog/post/view-main-page','is_main') . $position_html;
                        },
                        'filter' => StatusHelper::list(true),
                        'contentOptions' => SettingsWidget::setConfig('is_main',$user_settings['hide-col']??null),
                        'headerOptions' => SettingsWidget::setConfig('is_main',$user_settings['hide-col']??null),
                        'filterOptions' => SettingsWidget::setConfig('is_main',$user_settings['hide-col']??null,['class' => 'minw-100px']),
                    ],
                    [
                        'class' => CustomActionColumn::className(),
                        'header' => SettingsWidget::widget([
                            'model' => 'post',
                            'count_page' => 10,
                            'hide_col' => $user_settings['hide-col']??null,
                            'attribute' => [
                                'id' => 'ID',
                                'media_id' => 'Обложка',
                                'title' => 'Название',
                                'alias' => 'Алиас',
                                'category_id' => 'Категория',
                                'country_id' => 'Страна',
                                'description' => 'Описание',
                                'views' => 'Просмотры',
                                'likes' => 'Лайки',
                                'links' => 'Ссылки',
                                'published' => 'Публикация',
                                'is_main' => 'Пост на гл.',
                                'status' => 'Статус',
                            ]
                        ]),
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'update' => function($url, $model, $index) {
                                return Html::tag(
                                    'a',
                                    '',[
                                    'href' => $url,
                                    'title' => 'Редактировать статью',
                                    'aria-label' => 'Редактировать статью',
                                    'class' => 'grid-option fa fa-pencil',
                                    'data-pjax' => '0'
                                ]);
                            },
                            'delete' => function($url, $model, $index) {
                                return Html::tag(
                                    'a',
                                    '',[
                                    'href' => $url,
                                    'title' => 'Удалить статью',
                                    'aria-label' => 'Удалить статью',
                                    'class' => 'grid-option fa fa-trash',
                                    'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                                    'data-method' => 'post',
                                    'data-pjax' => '0'
                                ]);
                            }
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
