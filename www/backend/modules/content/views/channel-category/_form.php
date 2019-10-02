<?php

use alexeevdv\yii\BootstrapToggleWidget;
use backend\modules\content\models\ChannelCategoryContent;
use backend\modules\content\widgets\add_block_widget\AddBlockWidget;
use backend\modules\content\widgets\content_block_widget\ContentBlockWidget;
use vova07\imperavi\Asset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\content\models\ChannelCategory */
/* @var $seo backend\modules\content\models\SeoData */
/* @var $route backend\modules\content\models\SlugManager */
/* @var $form yii\widgets\ActiveForm */
/* @var $content backend\modules\content\models\ChannelCategoryContent */
?>

<div class="channel-category-form">
    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'main-form']); ?>

        <?= $form->field($model, 'channel_id')->hiddenInput()->label(false) ?>

        <div class="col-md-9">
            <div class="box">
                <div class="box-body">
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::input('hidden', 'redirect', 0) ?>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                <?= Html::button(
                    'Сохранить и выйти',
                    [
                        'class' => 'btn btn-primary save-page',
                        'data-redirect' => 1
                    ]
                ) ?>
                <a href="<?= Url::to(['/content/channel-category', 'channel_id' => $model->channel_id]) ?>" class="btn btn-primary">Отмена</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Атрибуты страницы</h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <?= $form->field($model, 'status')
                        ->widget(BootstrapToggleWidget::class, ['labelEnabled' => 'Вкл', 'labelDisabled' => 'Выкл']) ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>

<?php
Asset::register($this);

if(Yii::$app->session->hasFlash('errors')) {
    Yii::$app->session->getFlash('errors');
}
if(Yii::$app->session->hasFlash('success')) {
    Yii::$app->session->getFlash('success');
}

$js = <<< JS
$('.save-page').on('click', function() {
    $('input[name=redirect]').val($(this).data('redirect'));
    $('#main-form').submit();
});
JS;

$this->registerJs($js, VIEW::POS_END);
?>