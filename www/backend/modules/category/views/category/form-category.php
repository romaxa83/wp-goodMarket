<?php

use backend\modules\category\CategoryAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\widgets\SeoWidget;
use backend\modules\filemanager\widgets\FileInput;
use backend\widgets\langwidget\LangWidget;
use backend\widgets\sync_alias_widget\SyncAliasWidget;
use common\controllers\AccessController;

CategoryAsset::register($this);
?>
<?php $form = ActiveForm::begin(['id' => 'form-category', 'method' => 'POST', 'action' => Url::toRoute('/category/category/edit?id=' . $model->id)]); ?>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li><a href="#tab_1" data-toggle="tab" aria-expanded="false">База</a></li>
        <li class="active"><a href="#tab_2" data-toggle="tab" aria-expanded="false">Категория</a></li>
        <li><a href="#tab_3" data-toggle="tab" aria-expanded="true">SEO</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane" id="tab_1">
            <div class="form-group">
                <label class="control-label" for="category-stock_id">Идентификатор категорий</label>
                <input id="category-stock_id" class="form-control" name="Category[stock_id]" value="<?php echo $model->id ?>" readonly="readonly">
            </div>
            <div class="form-group field-category-parent_name required">
                <label class="control-label" for="category-parent_name">Родительская категория</label>
                <input type="text" id="category-parent_name" class="form-control" name="Category[parent_name]" value="<?php echo $parent_name ?>" readonly="readonly">
            </div>
            <?php
            echo LangWidget::widget(['model' => $model, 'fields' => [
                ['type' => 'text', 'name' => 'name'],
            ]]);
            ?>
            <div class="form-group field-category-status required">
                <label class="control-label" for="category-status">Статус</label>
                <input type="text" id="category-status" class="form-control" name="Category[status]" value="<?php echo ($model->publish_status == 1) ? 'Вкл. на складе' : 'Откл. на складе' ?>" readonly="readonly">
            </div>
        </div>
        <div class="tab-pane active" id="tab_2">
            <?php
            echo $form->field($model, 'alias');
            echo SyncAliasWidget::widget(['field_donor_name'=>'Category[name]', 'field_recipient_name'=>'Category[alias]']);
            echo $form->field($model, 'rating');
            echo $form->field($model, 'media_id')->widget(FileInput::className(), [
                'buttonTag' => 'button',
                'buttonName' => 'Browse',
                'buttonOptions' => ['class' => 'btn btn-default'],
                'options' => ['class' => 'form-control', 'readonly' => 'readonly'],
                'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                'thumb' => 'original',
                'imageContainer' => '.img',
                'pasteData' => FileInput::DATA_ID, // FileInput::DATA_URL
                'callbackBeforeInsert' => 'function(e, data) {console.log( data );}',
                'defaultTag' => 'category',
            ]);
            ?>
            <div class="preview-chosen-image" data-id="category-media_id-btn">
                <?php
                    $media = $model->getMedia()->one();
                    if(!is_null($media)){
                        if(!file_exists(Url::to('/admin' . $media->url))){
                            echo '<img src="'. Url::to('/admin' . $media->url) .'" alt="'. $media->alt .'">';
                        }else{
                            echo '<img src="'. Url::to('/admin/img/not-images.png') .'">';
                        }
                    }
                ?>
            </div>
            <?php echo $form->field($model, 'publish')->dropDownList([0 => 'Отключить', 1 => 'Включить']); ?>
        </div>
        <?php if(AccessController::checkPermission('seo/default/create-form')):?>
            <div class="tab-pane" id="tab_3">
                <?php echo SeoWidget::widget(['id' => $model->stock_id]); ?>
            </div>
        <?php endif;?>
    </div>
</div>
<div class="form-group">
    <?php echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'save', 'value' => '/category/category/edit?id=' . $model->id]) ?>
    <?php echo (!isset($page)) ? Html::submitButton('Сохранить и выйти в список', ['class' => 'btn btn-primary', 'name' => 'save', 'value' => '/category/category/']) : FALSE; ?>
    <a href="<?php echo Url::to(['/category/category']) ?>" class="btn btn-danger">Отмена</a>
</div>
<?php ActiveForm::end(); ?>
