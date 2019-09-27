<?php
use app\modules\reviews\ReviewsAsset;
use backend\widgets\hide_col\HideColWidget;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use jino5577\daterangepicker\DateRangePicker;
use common\service\CacheProductService;
use common\controllers\AccessController;
use backend\modules\product\models\CustomActionColumn;
/* @var $user_settings */

$this->title = 'Отзывы';
$this->params['breadcrumbs'][] = $this->title;
ReviewsAsset::register($this);
?>
<?php Modal::begin([
    'header' => '<h4>Оставить ответ на отзыв</h4>',
    ]);

Modal::end();?>
<div class="app-default-index">
    <div class="row">
        <div class="col-xs-12">
            <div class="row mb-15">
                <div class="col-xs-6">
                    <?= HideColWidget::widget([
                        'model' => 'review',
                        'hide_col' => $user_settings['hide-col'],
                        'attribute' => [
                            'product' => 'Товар',
                            'user' => 'Пользователь',
                            'date' => 'Дата публикация',
                            'rating' => 'Оценка',
                            'text' => 'Текст',
                            'status' => 'Статус',
                            'action' => 'Управление'
                        ]
                    ])?>
                </div>
            </div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Настройки отзывов</h3>
                </div>
                <div class="designations">
                    <div class="design">
                        <div class="sign">
                            <span class="mark-txt">- На отзыв нет ответа</span>
                            <div class="not-answered-review-square"></div>
                        </div>
                        <div class="sign">
                            <span class="mark-txt">- На отзыв есть ответ</span>
                            <div class="answered-review-square"></div>
                        </div>
                        <div class="sign">
                            <span class="mark-txt">- Ответ</span>
                            <div class="answer-square"></div>
                        </div>
                    </div>
                </div>
                <div class="box-body">

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'showFooter'=>true,
                        'tableOptions' => [
                            'id' => 'review-table',
                            'class'=>'table table-bordered'
                        ],
                        'rowOptions'=>function($model){
                            if(($model['answer_id']==0) && ($model['answered']==0))
                                return ['class'=>'not-answered-review-row'];
                            else if(($model['answer_id']==0) && ($model['answered']==1))
                                return ['class'=>'answered-review-row'];
                            else return ['class'=>'answer-row'];
                        },
                        'showFooter' => false,
                        'summary' => '',
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'contentOptions' => function($model){
                                    if($model['answer_id'] !== 0) return ['class'=>'answer'];
                                        return [];
                                },
                                //'footer_blocks' => 1

                            ],
                            [
                                'attribute'=>'product_name',
                                'format'=>'text',
                                'label'=>'Товар',
                                'contentOptions' => function($model) use ($user_settings){
                                    if($model['answer_id'] !== 0){
                                        return HideColWidget::setConfig('product',$user_settings['hide-col'],['class' => 'answer']) ;
                                    }
                                    return HideColWidget::setConfig('product',$user_settings['hide-col']);
                                },
                                'headerOptions' => HideColWidget::setConfig('product',$user_settings['hide-col']),
                                'filterOptions' => HideColWidget::setConfig('product',$user_settings['hide-col']),
                            ],
                            [

                                'attribute'=>'full_name',
                                'format'=>'text',
                                'label'=>'Пользователь',
                                'contentOptions' => function($model) use ($user_settings){
                                    if($model['answer_id'] !== 0){
                                        return HideColWidget::setConfig('user',$user_settings['hide-col'],['class' => 'answer']) ;
                                    }
                                    return HideColWidget::setConfig('user',$user_settings['hide-col']);
                                },
                                'headerOptions' => HideColWidget::setConfig('user',$user_settings['hide-col']),
                                'filterOptions' => HideColWidget::setConfig('user',$user_settings['hide-col']),
                            ],
                            [
                                'attribute'=>'date',
                                'label'=>'Дата публикации',
                                'value'=>function($model){
                                    return date('Y-m-d H:i:s', strtotime($model['date']));
                                },
                                'filter' => DateRangePicker::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'date',
                                    'pluginOptions' => [
                                    'format' => 'YYYY-MM-DD',
                                    'autoUpdateInput' => false
                                    ]
                                ]),
                                'contentOptions' => function($model) use ($user_settings){
                                    if($model['answer_id']!=0){
                                        return HideColWidget::setConfig('date',$user_settings['hide-col'],['class' => 'answer']) ;
                                    }
                                    return HideColWidget::setConfig('date',$user_settings['hide-col']);
                                },
                                'headerOptions' => HideColWidget::setConfig('date',$user_settings['hide-col']),
                                'filterOptions' => HideColWidget::setConfig('date',$user_settings['hide-col']),
                            ],
                            [
                                'attribute'=>'rating',
                                'format'=>'text',
                                'label'=>'Оценка',
                                'value'=>function($model){
                                    if($model['answer_id']==0){
                                        return $model['rating'];
                                    }else return '';
                                },
                                'contentOptions' => function($model) use ($user_settings){
                                    if($model['answer_id']!=0){
                                        return HideColWidget::setConfig('rating',$user_settings['hide-col'],['class' => 'answer']) ;
                                    }
                                    return HideColWidget::setConfig('rating',$user_settings['hide-col']);
                                },
                                'headerOptions' => HideColWidget::setConfig('rating',$user_settings['hide-col']),
                                'filterOptions' => HideColWidget::setConfig('rating',$user_settings['hide-col']),
                            ],
                             [
                                 'attribute'=>'text',
                                 'format'=>'text',
                                 'label'=>'Текст',
                                 'contentOptions' => function($model) use ($user_settings){
                                     if($model['answer_id']!=0){
                                         return HideColWidget::setConfig('text',$user_settings['hide-col'],['class' => 'answer']) ;
                                     }
                                     return HideColWidget::setConfig('text',$user_settings['hide-col']);
                                 },
                                 'headerOptions' => HideColWidget::setConfig('text',$user_settings['hide-col'],['style' => 'text-align:center']),
                                 'filterOptions' => HideColWidget::setConfig('text',$user_settings['hide-col']),
                            ],
                            [
                                'attribute'=>'publication',
                                'format'=>'raw',
                                'label'=>'Статус',
                                'value' => function($model, $key, $index, $column){
                                    $access = AccessController::isView(Yii::$app->controller, 'update-status');
                                    if($access){
                                        if($model['answer_id']==0){
                                            $checked = ($model['publication']==1) ? 'true' : '';
                                            $options = [
                                                'id' => 'cd_'.$model['id'],
                                                'class' => 'tgl tgl-light publish-toggle status-toggle',
                                                'data-id' => $model['id'],
                                                'data-url' => Url::to(['update-status'])
                                            ];

                                            return  Html::beginTag('div') .
                                                    Html::checkbox('status', $checked, $options) .
                                                    Html::label('',  'cd_'.$model['id'], ['class' => 'tgl-btn']) .
                                                    Html::endTag('div');
                                        }else return '';
                                    }
                                },
                                'contentOptions' => function($model) use ($user_settings){
                                    if($model['answer_id']!=0){
                                        return HideColWidget::setConfig('status',$user_settings['hide-col'],['class' => 'answer']) ;
                                    }
                                    return HideColWidget::setConfig('status',$user_settings['hide-col']);
                                },
                                'headerOptions' => HideColWidget::setConfig('status',$user_settings['hide-col']),
                                'filterOptions' => HideColWidget::setConfig('status',$user_settings['hide-col']),
                            ],
                            [
                                'class' => CustomActionColumn::className(),
                                'template' => '{update}',
                                'filter' => '<a href="' . Url::to(['/reviews/reviews'], TRUE) . '"><i class="grid-option fa fa-filter" title="Сбросить фильтр"></i></a>',
                                'header'=>'Управление',
                                'contentOptions' => HideColWidget::setConfig('action',$user_settings['hide-col']),
                                'headerOptions' => HideColWidget::setConfig('action',$user_settings['hide-col'],['width' => '100']),
                                'filterOptions' => HideColWidget::setConfig('action',$user_settings['hide-col']),
                                'buttons' => [
                                    'update' => function($url, $model, $key) {
                                        $access = AccessController::isView(Yii::$app->controller, 'show-answer-form-back');
                                        if($access){
                                            if($model['answer_id']==0){
                                                return Html::tag(
                                                    'a',
                                                    '',[
                                                    'href' => '#',
                                                    'title' => 'Ответить',
                                                    'aria-label' => 'Ответить',
                                                    'style' => 'color:rgb(63,140,187)',
                                                    'class' => 'grid-option fa fa-pencil answer-window',
                                                    'data-toggle' => \Yii::t('yii', 'modal'),
                                                    'data-target' => \Yii::t('yii', '#w0'),
                                                    'data-parent_id' => $model['id'],
                                                    'data-pjax' => '1'
                                                ]);
                                            }
                                        }
                                    },
                                ]
                            ],

                         ],

                    ]);?>

                </div>
            </div>
        </div>
    </div>
</div>
