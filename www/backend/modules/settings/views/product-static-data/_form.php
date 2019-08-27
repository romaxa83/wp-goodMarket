<?php

use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
/* @var $model */
?>
<div class="footerblocks-form">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <?php $form = ActiveForm::begin(); ?>
                <div class="box-body">
                    <div class="col-xs-8">

                        <?php echo $form->field($model, 'title')->label('Заголовок'); ?>
                    </div>
                    <div class="col-xs-12">
                        <?php echo $form->field($model,'description')
                            ->widget(Widget::className(), [
                                'settings' => [
                                    'lang' => 'ru',
                                    'minHeight' => 200,
                                    'plugins' => [
                                        'clips',
                                        'fullscreen',
                                    ],
                                ],
                            ])->label('Описание')?>

                        <div class="form-group">
                            <?= Html::submitButton('Редактировать', ['class' => 'btn btn-primary mr-15']) ?>
                            <?= Html::a('Отмена',Url::to(['/settings/product-static-data/index']),['class' => 'btn btn-danger'])?>
                        </div>

                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>