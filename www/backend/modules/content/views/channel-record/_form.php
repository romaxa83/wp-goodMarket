<?php

use alexeevdv\yii\BootstrapToggleWidget;
use backend\modules\content\models\ChannelCategory;
use backend\modules\content\widgets\add_block_widget\AddBlockWidget;
use backend\modules\content\widgets\content_block_widget\ContentBlockWidget;
use backend\modules\content\widgets\select_template_widget\SelectTemplateWidget;
use backend\modules\content\widgets\sync_alias_widget\SyncAliasWidget;
use backend\modules\filemanager\widgets\FileInput;
use vova07\imperavi\Asset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\content\models\ChannelRecord */
/* @var $seo backend\modules\content\models\SeoData */
/* @var $route backend\modules\content\models\SlugManager */
/* @var $form yii\widgets\ActiveForm */
/* @var $contentBlocks backend\modules\content\models\ChannelContent */
/* @var $categories backend\modules\content\models\ChannelCategory[] */
/* @var $action string */
/* @var $structure string */
?>

<div class="channel-record-form">
    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'main-form']); ?>
        <?= $form->field($model, 'channel_id')->hiddenInput()->label(false) ?>
        <div class="col-md-9">
            <div class="box">
                <div class="box-body">
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Контент</a></li>
                    <li><a href="#tab_2" data-toggle="tab" aria-expanded="true">SEO</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <?php $blocks = unserialize($structure); ?>
                        <?php foreach($blocks as $i => $block) : ?>
                            <?php
                            $text = isset($block['content']) ? $block['content'] : '';
                            if(is_object($block)) {
                                $errors = !empty($block->getErrors()) ? true : false;
                            }
                            else {
                                $errors =false;
                            }
                            ?>
                            <?= ContentBlockWidget::widget([
                                'type' => $block['type'],
                                'block_id' => $i,
                                'content_id' => '',
                                'value' => isset($block['text']) ? $block['text'] : $text,
                                'name' => $block['name'],
                                'label' => $block['label'],
                                'errors' => $errors
                            ]); ?>
                        <?php endforeach; ?>
                    </div>
                    <div class="tab-pane" id="tab_2">
                        <?= $form->field($seo, 'title')->textInput() ?>
                        <?= $form->field($seo, 'description')->textarea() ?>
                        <?= $form->field($seo, 'keywords')->textInput() ?>
                    </div>
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
                <a href="<?= Url::to(['/content/channel-record', 'channel_id' => $model->channel_id]) ?>" class="btn btn-primary">Отмена</a>
            </div>
        </div>
        <div class="col-md-3">
            <?php if($action != 'create') : ?>
                <a
                    href="<?= Yii::$app->urlManagerFrontend->createUrl([$route->route, 'template' => $route->template, 'post_id' => $model->id]) ?>"
                    target="_blank" class="btn btn-primary btn-block margin-bottom">
                    Перейти на страницу
                </a>
            <?php endif; ?>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Атрибуты страницы</h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <?= Html::label('Псевдоним', '', ['class' => 'control-label']) ?>
                        <?= Html::textInput('slug[slug]', $route->slug, array('class' => 'form-control')) ?>
                    </div>
                    <?= SyncAliasWidget::widget(['field_donor_name'=>'ChannelRecord[title]', 'field_recipient_name'=>'slug[slug]']) ?>
                    <?= SelectTemplateWidget::widget([
                        'slugId' => $route->id,
                        'slugRoute' => $route->route,
                        'routeToAction' => 'page/get-route-for-template',
                        'value' => $route->template,
                        'templates' => Yii::$app->getModule('content')->params['templates'],
                    ]) ?>

                    <?= $form->field($model, 'cover_id')->widget(FileInput::className(), [
                        'buttonTag' => 'button',
                        'buttonName' => 'Добавить обложку',
                        'buttonOptions' => ['class' => 'btn btn-default'],
                        'options' => ['class' => 'form-control', 'readonly' => 'readonly'],
                        'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                        'thumb' => 'original',
                        'imageContainer' => '.img',
                        'pasteData' => FileInput::DATA_ID,
                    ]) ?>

                    <?= $form->field($model, 'status')
                        ->widget(BootstrapToggleWidget::class, ['labelEnabled' => 'Вкл', 'labelDisabled' => 'Выкл']) ?>
                </div>
            </div>

            <?php if(! empty($categories)) : ?>
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Категории</h3>
                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php foreach($categories as $category) : ?>
                            <div class="form-group record-categories">
                                <?php $status = isset($model->id) ? ChannelCategory::getStatusForRecord($category->id, $model->id) : false; ?>
                                <?= Html::checkbox(
                                        "categories[{$category->id}]",
                                        $status,
                                        [
                                            'label' => $category->title,
                                            'value' => $category->id
                                        ]
                                ) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
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
$('#main-form').on('submit', function(e) {
    if($('.record-categories').find('input:checked').length === 0 && $('.record-categories').length > 0) {
        alert('Вы не указали не одной категории. Пожалуйста выберите категорию.');
        return false;
    }
});
$('#main-form').on('afterValidate', function () {
       if ($('.has-error').length > 0) {
           var id = $('.has-error').first().parents('.tab-pane').attr('id');
           $('a[href="#' + id + '"]').click();
       }
   });
JS;

$this->registerJs($js, VIEW::POS_END);
?>