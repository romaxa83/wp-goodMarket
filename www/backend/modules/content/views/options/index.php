<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Опции';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-primary">
    <?php $form = ActiveForm::begin(['class' => 'form-horizontal']); ?>
    <div class="box-body">
        <div class="row">
            <div class="form-group">
                <label class="col-sm-2 control-label">Главная страница</label>
                <div class="col-sm-10">
                    <input type="hidden" name="options[1][name]" value="main_page_id">
                    <?= Html::dropDownList('options[1][value]',
                        isset($options['main_page_id']['value']) ? $options['main_page_id']['value'] : '',
                        ArrayHelper::map($pages, 'id', 'title'),
                        ['class' => 'form-control' , 'prompt' => 'Выберете главную страницу'])
                    ?>
                </div>

            </div>
        </div>
    </div>
    <div class="box-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
if(Yii::$app->session->hasFlash('errors')) {
Yii::$app->session->getFlash('errors');
}
if(Yii::$app->session->hasFlash('success')) {
    Yii::$app->session->getFlash('success');
}