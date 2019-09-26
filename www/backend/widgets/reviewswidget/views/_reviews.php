<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use kartik\rating\StarRating;
use yii\captcha\Captcha;
$user = Yii::$app->user->identity;
$user_id = (!Yii::$app->user->isGuest)?$user->id:0;
?>

<div class="row row-title">
    <div class="col-xs-12">
        <h4 class="pre-title collapsed" data-toggle="collapse" data-target="#previews" aria-expanded="false">
            Отзывы</h4>
    </div>
</div>
<div class="row collapse" id="previews">
    <div class="col-xs-12">
        <?php if ($form_visible==true): ?>
            <div class="row">
                <div class="col-xs-12">
                    <div class="wrap-border wrap-reviews">
                        <?php
                        $form = ActiveForm::begin(
                                        ['options' => [
                                                'id' => 'review-form',
                                                'data-pjax' => 1,
                                            ]
                                        ]
                                )
                        ?>
                        <?= $form->field($model, 'text')->textarea(['rows' => '6'])->label('Оставьте отзыв', ['for' => 'review']) ?>
                        <?= $form->field($model, 'verifyCode')->widget(Captcha::className())->label('Код подтверждения') ?>
                        <div class="input-group">
                            <div class="wrap">
                                <div class="rating">
                                    <?=
                                    $form->field($model, 'rating')->widget(StarRating::classname(), [
                                        'pluginOptions' => [
                                            'theme' => 'krajee-svg',
                                            'filledStar' => '<span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>',
                                            'size' => '1.13em',
                                            'emptyStar' => '<span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>',
                                            'showClear' => false,
                                            'showCaption' => false
                                        ]
                                    ])->label(FALSE);
                                    ?>
                                </div>
                            </div>
                            <p>Поставьте оценку товара</p>
                            <?=
                                Html::tag(
                                    'button', 'Оставить отзыв', [
                                    'title' => 'Оставить отзыв',
                                    'type' => 'button',
                                    'aria-label' => 'Оставить отзыв',
                                    'class' => 'btn btn-main v3 pull-right add',
                                    'data-action' => 'addReview',
                                    'data-product_id' => $product_id,
                                    'data-user_id' => $user_id,
                                    'data-pjax' => '1'
                                ]);
                            ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        <?php endif ?>
        <div class="row row-reviews">
            <div class="col-xs-12">
                <?php \yii\widgets\Pjax::begin([
                    'enablePushState' => true,
                    'id' => 'reviews-product',
                    'timeout' => 4000
                ]); ?>
                <?=
                ListView::widget([
                    'dataProvider' => $dataProvider,
                    'options' => [
                        'tag' => 'div',
                        'class' => 'list-wrapper',
                        'id' => 'list-wrapper',
                    ],
                    'emptyText' => '',
                    'layout' => "{items}\n{pager}",
                    'itemView' => function ($model, $key, $index, $widget) use($user_id, $user){
                        if ($model['answer_id'] != 0){
                            $type = 'answer';
                        }else{
                            $type = 'review';
                        }
                        return $this->render('_list_item', ['model' => $model, 'type' => $type, 'user_id'=>$user_id, 'user' => $user]);
                    },
                    'itemOptions' => [
                        'tag' => false,
                    ],
                    'pager' => [
                        'disableCurrentPageButton' => true,
                        'maxButtonCount' => 3,
                    ],
                ]);
                ?>
                <?php \yii\widgets\Pjax::end(); ?>
            </div>
            <script>
                $(document).on('pjax:end', function() {
                    $('html, body').animate({
                        scrollTop: ($("#reviews-product").offset().top - 450)
                    }, 0);
                })
            </script>
        </div>
    </div>
</div>