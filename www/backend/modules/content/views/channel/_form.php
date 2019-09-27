<?php

use alexeevdv\yii\BootstrapToggleWidget;
use backend\modules\content\widgets\add_block_widget\AddBlockWidget;
use backend\modules\content\widgets\content_block_widget\ContentBlockWidget;
use backend\modules\content\widgets\select_template_widget\SelectTemplateWidget;
use backend\modules\content\widgets\sync_alias_widget\SyncAliasWidget;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use vova07\imperavi\Asset;

/* @var $this yii\web\View */
/* @var $model backend\modules\content\models\Channel */
/* @var $seo backend\modules\content\models\SeoData */
/* @var $route backend\modules\content\models\SlugManager */
/* @var $contentBlocks backend\modules\content\models\ChannelContent */
/* @var $commonFields backend\modules\content\models\ChannelRecordsCommonField */
/* @var $form yii\widgets\ActiveForm */
/* @var $action string */
?>

<div class="channel-form">
    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'main-form']); ?>
        <div class="col-lg-8">
            <div class="box">
                <div class="box-body">
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false" data-tab="tab_1">Контент</a></li>
                    <li><a href="#tab_2" data-toggle="tab" aria-expanded="true" data-tab="tab_2">SEO</a></li>
                    <li><a href="#tab_3" data-toggle="tab" aria-expanded="true" data-tab="tab_3">Общие поля записей</a></li>
                    <li><a href="#tab_4" data-toggle="tab" aria-expanded="true" data-tab="tab_4">Структура записи</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <?php if(isset($contentBlocks)) : ?>
                            <?php foreach($contentBlocks as $i => $block) : ?>
                                <?= ContentBlockWidget::widget([
                                    'type' => $block['type'],
                                    'block_id' => $block['id'],
                                    'content_id' => $block['id'],
                                    'value' => $block['content'],
                                    'name' => $block['name'],
                                    'label' => $block['label']
                                ]); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <div id="channel-new-content"></div>
                    </div>
                    <div class="tab-pane" id="tab_2">
                        <?php if($seo->id) : ?>
                        <?= $form->field($seo, 'id')->hiddenInput()->label(false) ?>
                        <?php endif; ?>
                        <?= $form->field($seo, 'title')->textInput() ?>
                        <?= $form->field($seo, 'description')->textarea() ?>
                        <?= $form->field($seo, 'keywords')->textInput() ?>
                    </div>
                    <div id="tab_3" class="tab-pane">
                        <div id="records-common-fields">
                            <?php if(isset($commonFields)) : ?>
                                <?php foreach($commonFields as $i => $block) : ?>
                                    <?= ContentBlockWidget::widget([
                                        'type' => $block['type'],
                                        'block_id' => $block['id'],
                                        'content_id' => $block['id'],
                                        'value' => $block['content'],
                                        'name' => $block['name'],
                                        'label' => $block['label'],
                                        'group' => 'commonFields'
                                    ]); ?>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <div id="new-common-fields"></div>
                        </div>
                    </div>
                    <div id="tab_4" class="tab-pane">
                        <?= $form->field($model,'record_structure')->widget(MultipleInput::className(), [
                            'allowEmptyList'    => false,
                            'enableGuessTitle'  => true,
                            'columns' => [
                                [
                                    'name' => 'name',
                                    'type' => 'textInput',
                                    'title' => 'Имя'
                                ],
                                [
                                    'name' => 'label',
                                    'type' => 'textInput',
                                    'title' => 'Подпись'
                                ],
                                [
                                    'name' => 'type',
                                    'type' => 'dropDownList',
                                    'title' => 'Тип блока',
                                    'options' => [
                                        'prompt' => 'Выбор блока',
                                        'class' => 'form-control',
                                    ],
                                    'items' => Yii::$app->getModule('content')->params['blockTypes']
                                ],
                            ],
                            'data' => unserialize($model->record_structure),
                        ]) ?>
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
                <a href="<?= Url::to(['/content/channel']) ?>" class="btn btn-primary">Отмена</a>
            </div>
        </div>
        <div class="col-lg-4">
            <?php if($action != 'create') : ?>
                <a
                    href="<?= Yii::$app->urlManagerFrontend->createUrl([$route->route, 'template' => $route->template]) ?>"
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
                    <?= SyncAliasWidget::widget(['field_donor_name'=>'Channel[title]', 'field_recipient_name'=>'slug[slug]']) ?>
                    <?= SelectTemplateWidget::widget([
                        'slugId' => $route->id,
                        'slugRoute' => $route->route,
                        'routeToAction' => 'page/get-route-for-template',
                        'value' => $route->template,
                        'templates' => Yii::$app->getModule('content')->params['templates'],
                    ]) ?>

                    <?= $form->field($model, 'status')
                        ->widget(BootstrapToggleWidget::class, ['labelEnabled' => 'Вкл', 'labelDisabled' => 'Выкл']) ?>
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
                    <div class="form-group">
                        <h4>Добавление контента</h4>
                        <?= AddBlockWidget::widget([
                            'parentContainerId' => 'channel-new-content'
                        ]) ?>
                    </div>
                    <div class="form-group">
                        <h4>Добавление общих полей</h4>
                        <?= AddBlockWidget::widget([
                            'parentContainerId' => 'new-common-fields',
                            'group' => 'commonFields'
                        ]) ?>
                    </div>
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
