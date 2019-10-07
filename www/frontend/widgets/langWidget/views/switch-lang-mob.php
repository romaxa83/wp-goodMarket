<?php if(Yii::$app->language === 'ru') : ?>
    <span class="active">ru</span>
    <a href="<?= \yii\helpers\Url::base() . '/ua' ?>">ua</a>
<?php else : ?>
    <a href="<?= \yii\helpers\Url::base() . '/ru' ?>">ru</a>
    <span class="active">ua</span>
<?php endif; ?>