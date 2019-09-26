<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use dosamigos\multiselect\MultiSelect;
use yii\helpers\Html;
use common\controllers\AccessController;

if (isset($shop->id)) {
    $this->title = 'Редактирование магазина';
    $action = 'edit?id=' . $shop->id;
} else {
    $this->title = 'Добавление магазина';
    $action = 'add-shop';
}
if (Yii::$app->controller->action->id == 'edit') {
    $handler = '/admin/import/save-import/edit';
} else {
    $handler = '/admin/import/save-import/start-save';
}
?>



<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'form-export',
                            'action' => Url::to($handler)
                ]);
                ?>
                <div class="row">
                    <div class="col-xs-6">
                        <?php echo $form->field($shop, 'id')->input('number', ['readonly' => true]); ?>
                    </div>
                    <div class="col-xs-6">
                        <?php
                        echo $form->field($shop, 'update_frequency')->dropDownList(Yii::$app->getModule('import')->params['update_frequency']);
                        ?>
                    </div>
                </div>
                <div class="multiselect-hidden">
                    <?php
                    echo MultiSelect::widget([
                        'id' => 'def-multiselect',
                        'name' => 'def-multiselect',
                        'data' => [0 => 'p1', 2 => 'p2'],
                    ]);
                    ?>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <?php echo $form->field($shop, 'name')->input('text'); ?>
                    </div>
                    <div class="col-xs-6 link-shop">
                        <?php echo $form->field($shop, 'link')->input('text'); ?>
                        <button id="download-xml" type="button" class="btn btn-primary">Загрузить</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <?php
                        echo $form->field($shop, 'currency')->dropDownList(Yii::$app->getModule('import')->params['currency']);
                        ?>
                    </div>
                    <div class="col-xs-6">
                        <?php echo $form->field($shop, 'currency_value')->input('float', ['value' => 1]); ?>
                    </div>
                </div>
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a data-toggle="tab" href="#home">Соответствие полей карточки</a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#menu1">Соответствие категорий</a>
                    </li>
                    <?php if (Yii::$app->controller->action->id !== 'edit'): ?>
                        <li>
                            <a data-toggle="tab" href="#menu2">Seo шаблон</a>
                        </li>
                    <?php endif ?>
                </ul>
                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                        <div class="row">
                            <div class="col-xs-6 col-xs-12">
                                <h3>Поля с карточки товара</h3>
                            </div>
                            <div class="col-xs-6 col-xs-12">
                                <h3>Теги с файла</h3>
                            </div>
                        </div>
                        <div class="tag-from-file content"></div>
                    </div>
                    <div id="menu1" data-action="<?= Yii::$app->controller->action->id ?>" class="tab-pane fade">
                        <div class="row">
                            <div class="col-xs-6">
                                <h3>Категории с файла</h3>
                            </div>
                            <div class="col-xs-6">
                                <h3>Категории магазина</h3>
                            </div>
                        </div>
                        <div class="category-from-file content"></div>
                    </div>
                    <?php if (Yii::$app->controller->action->id !== 'edit' && AccessController::isView(Yii::$app->controller, Yii::$app->controller->action->id)): ?>
                        <div id="menu2" data-action="<?= Yii::$app->controller->action->id ?>" class="tab-pane fade content">
                            <?php echo $this->render('seo_generator'); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <div class="button-controle-export">
                        <?php echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'save', 'value' => '/admin/import/import/' . $action]) ?>
                        <?php echo Html::submitButton('Сохранить и выйти в список', ['class' => 'btn btn-primary', 'name' => 'save', 'value' => '/admin/import/import']) ?>
                        <a href="<?= Url::to('/admin/import/export') ?>" class="btn btn-danger">Отмена</a>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
