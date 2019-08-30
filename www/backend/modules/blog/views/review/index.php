<?php

use backend\modules\blog\helpers\DateHelper;
use backend\modules\referenceBooks\models\HotelReview as Review;
use backend\modules\blog\widgets\settings\SettingsWidget;
use kartik\date\DatePicker;
use yii\helpers\StringHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\blog\BlogAsset;
use backend\modules\blog\helpers\StatusHelper;
use backend\modules\blog\components\CustomActionColumn;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\blog\forms\search\HotelReviewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $user_settings */
/* @var $page */
/* @var $access backend\modules\user\useCase\Access */

$this->title = 'Список отзывов на отели';
$this->params['breadcrumbs'][] = $this->title;

BlogAsset::register($this);

?>
<div class=review-for-hotel-index">

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Список отзывов</h3>
        </div>
        <div class="box-body table-flexible">
            <?= GridView::widget([
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
                        'value' => function(Review $model){
                            return $model->id;
                        },
                        'contentOptions' => SettingsWidget::setConfig('id',$user_settings['hide-col']??null),
                        'headerOptions' => SettingsWidget::setConfig('id',$user_settings['hide-col']??null),
                        'filterOptions' => SettingsWidget::setConfig('id',$user_settings['hide-col']??null,['width' => '50']),
                    ],
                    [
                        'attribute' => 'avatar',
                        'label' => 'Аватарка',
                        'format' => 'raw',
                        'value' => function(Review $model){
                            return Html::img($model->avatar,['width' => '50px']);
                        },
                        'contentOptions' => SettingsWidget::setConfig('avatar',$user_settings['hide-col']??null),
                        'headerOptions' => SettingsWidget::setConfig('avatar',$user_settings['hide-col']??null),
                        'filterOptions' => SettingsWidget::setConfig('avatar',$user_settings['hide-col']??null),
                    ],
                    [
                        'attribute' => 'title',
                        'label' => 'Название',
                        'format' => 'raw',
                        'value' => function(Review $model){
                            return $model->title;
                        },
                        'contentOptions' => SettingsWidget::setConfig('title',$user_settings['hide-col']??null),
                        'headerOptions' => SettingsWidget::setConfig('title',$user_settings['hide-col']??null),
                        'filterOptions' => SettingsWidget::setConfig('title',$user_settings['hide-col']??null),
                    ],
                    [
                        'attribute' => 'description',
                        'label' => 'Отзыв',
                        'format' => 'raw',
                        'value' => function(Review $model){
                            return StringHelper::truncateWords(strip_tags($model->comment),10);
                        },
                        'contentOptions' => SettingsWidget::setConfig('description',$user_settings['hide-col']??null),
                        'headerOptions' => SettingsWidget::setConfig('description',$user_settings['hide-col']??null),
                        'filterOptions' => SettingsWidget::setConfig('description',$user_settings['hide-col']??null,['width' => '100px']),
                    ],
                    [
                        'attribute' => 'hotel_id',
                        'label' => 'Отель',
                        'format' => 'raw',
                        'value' => function(Review $model){
                            if(isset($model->hotel->name)){
                                return Html::a($model->hotel->name,['/referenceBooks/hotel/update', 'id' => $model->hotel_id]);
                            }
                            return '<span>Отеля нет</span>';
                        },
                        'contentOptions' => SettingsWidget::setConfig('hotel_id',$user_settings['hide-col']??null),
                        'headerOptions' => SettingsWidget::setConfig('hotel_id',$user_settings['hide-col']??null),
                        'filterOptions' => SettingsWidget::setConfig('hotel_id',$user_settings['hide-col']??null),
                    ],
                    [
                        'attribute' => 'fullName',
                        'label' => 'Пользователь',
                        'format' => 'raw',
                        'value' => function(Review $model){
                            if($model->user != '' && $model->user !== null){
                                return $model->user;
                            }
                            if($model->user_id !== null){
                                return Html::a($model->author->getFullName(),['/user/view','id' => $model->user_id]);
                            }
                        },
                        'contentOptions' => SettingsWidget::setConfig('fullName',$user_settings['hide-col']??null),
                        'headerOptions' => SettingsWidget::setConfig('fullName',$user_settings['hide-col']??null),
                        'filterOptions' => SettingsWidget::setConfig('fullName',$user_settings['hide-col']??null),
                    ],
                    [
                        'attribute' => 'date',
                        'label' => 'Дата',
                        'format' => 'raw',
                        'value' => function(Review $model){
                            if(isset($model->date) && !empty($model->date)){
                                return DateHelper::convertForAdmin($model->date);
                            }
                        },
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'language' => 'ru',
                            'type' =>DatePicker::TYPE_COMPONENT_APPEND,
                            'attribute' => 'date',
                            'options' => ['placeholder' => 'Дата'],
                            'convertFormat' => true,
                            'pluginOptions' => [
                                'autoUpdateInput' => false,
                                'format' => 'dd-MM-yyyy',
                                'autoclose'=>true,
                                'weekStart'=>1, //неделя начинается с понедельника
                            ]
                        ]),
                        'contentOptions' => SettingsWidget::setConfig('date',$user_settings['hide-col']??null),
                        'headerOptions' => SettingsWidget::setConfig('date',$user_settings['hide-col']??null),
                        'filterOptions' => SettingsWidget::setConfig('date',$user_settings['hide-col']??null,['class' => 'minw-200px']),
                    ],
                    [
                        'attribute' => 'vote',
                        'label' => 'Оценка',
                        'format' => 'raw',
                        'value' => function(Review $model){
                            return $model->vote;
                        },
                        'filter' => StatusHelper::listVote(),
                        'contentOptions' => SettingsWidget::setConfig('vote',$user_settings['hide-col']??null),
                        'headerOptions' => SettingsWidget::setConfig('vote',$user_settings['hide-col']??null),
                        'filterOptions' => SettingsWidget::setConfig('vote',$user_settings['hide-col']??null),
                    ],
                    [
                        'attribute' => 'status',
                        'label' => 'Статус',
                        'format' => 'raw',
                        'value' => function(Review $model){
                            return StatusHelper::checkBox($model,'/blog/review/status-change');
                        },
                        'filter' => StatusHelper::list(true),
                        'contentOptions' => SettingsWidget::setConfig('status',$user_settings['hide-col']??null),
                        'headerOptions' => SettingsWidget::setConfig('status',$user_settings['hide-col']??null),
                        'filterOptions' => SettingsWidget::setConfig('status',$user_settings['hide-col']??null,['class' => 'minw-100px']),
                    ],
                    [
                        'class' => CustomActionColumn::className(),
                        'header' => SettingsWidget::widget([
                            'model' => 'review-for-hotel',
                            'count_page' => $page,
                            'hide_col' => $user_settings['hide-col']??null,
                            'attribute' => [
                                'id' => 'ID',
                                'avatar' => 'Аватарка',
                                'title' => 'Название',
                                'description' => 'Отзыв',
                                'hotel_id' => 'Отель',
                                'fullName' => 'Пользователь',
                                'date' => 'Дата',
                                'vote' => 'Оценнка',
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
                                    'title' => 'Редактировать обзор',
                                    'aria-label' => 'Редактировать обзор',
                                    'class' => 'grid-option fa fa-pencil',
                                    'data-pjax' => '0'
                                ]);
                            },
                            'delete' => function($url, $model, $index) {
                                return Html::tag(
                                    'a',
                                    '',[
                                    'href' => $url,
                                    'title' => 'Удалить обзор',
                                    'aria-label' => 'Удалить обзор',
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
