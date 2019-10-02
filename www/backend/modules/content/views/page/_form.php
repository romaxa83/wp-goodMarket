<?php

use backend\modules\content\widgets\add_block_widget\AddBlockWidget;
use backend\modules\content\widgets\content_block_widget\ContentBlockWidget;
use backend\modules\content\widgets\select_template_widget\SelectTemplateWidget;
use backend\modules\content\widgets\sync_alias_widget\SyncAliasWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use vova07\imperavi\Asset;
use vova07\imperavi\Widget;
use alexeevdv\yii\BootstrapToggleWidget;
use backend\widgets\langwidget\LangWidget;

/* @var $this yii\web\View */
/* @var $model backend\modules\content\models\Page */
/* @var $form yii\widgets\ActiveForm */
/** @var $seo backend\modules\content\models\PageMeta */
/** @var $text backend\modules\content\models\PageText */
/** @var $slug backend\modules\content\models\SlugManager */
?>

<div class="page-form">
    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'main-form']); ?>
        <div class="col-lg-8">
            <div class="box">
                <div class="box-body">
                    <?= LangWidget::widget([
                        'model' => $langModel,
                        'fields' => [
                            ['type' => 'text', 'name' => 'title'],
                        ]
                    ]); ?>
                </div>
            </div>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Контент</a></li>
                    <li><a href="#tab_2" data-toggle="tab" aria-expanded="true">SEO</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <?php if(isset($textBlocks)) : ?>
                            <?php foreach($textBlocks as $i => $block) : ?>
                                <?= ContentBlockWidget::widget([
                                    'type' => $block['type'],
                                    'block_id' => $block['id'],
                                    'content_id' => $block['id'],
                                    'value' => $block['text'],
                                    'name' => $block['name'],
                                    'label' => $block['label']
                                ]); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <div id="page-new-content"></div>
                    </div>
                    <div class="tab-pane" id="tab_2">
                        <?= LangWidget::widget([
                            'model' => $langSeo,
                            'fields' => [
                                ['type' => 'text', 'name' => 'title'],
                                ['type' => 'text', 'name' => 'description'],
                                ['type' => 'text', 'name' => 'keywords'],
                            ],
                            'idTab' => 'seo_filed'
                        ]); ?>
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
                <a href="<?= Url::to(['/content/page']) ?>" class="btn btn-primary">Отмена</a>
            </div>
        </div>
        <div class="col-lg-4">
            <a href="<?= Yii::$app->urlManagerFrontend->createUrl([$slug->route, 'template' => $slug->template]) ?>" target="_blank" class="btn btn-primary btn-block margin-bottom">Перейти на страницу</a>
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
                        <?= Html::textInput('slug[slug]', $slug->slug, array('class' => 'form-control')) ?>
                    </div>
                    <?= SyncAliasWidget::widget(['field_donor_name'=>'PageLang[ru][title]', 'field_recipient_name'=>'slug[slug]']) ?>
                    <?= SelectTemplateWidget::widget([
                        'slugId' => $slug->id,
                        'slugRoute' => $slug->route,
                        'routeToAction' => 'page/get-route-for-template',
                        'value' => $slug->template,
                        'templates' => Yii::$app->getModule('content')->params['templates'],
                        ]) ?>

                    <?= $form->field($model, 'status')->widget(BootstrapToggleWidget::class, ['labelEnabled' => 'Вкл', 'labelDisabled' => 'Выкл']) ?>
                </div>
            </div>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Инструменты разработчика</h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <?= AddBlockWidget::widget([
                        'parentContainerId' => 'page-new-content'
                    ]) ?>
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
