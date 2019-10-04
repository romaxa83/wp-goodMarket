<li class="nav-item desctop-menu__lang dropdown-click lang-dropdown">
    <span class="nav-link dropdown-toggle"><?= Yii::$app->language ?></span>
    <div class="dropdown-menu dropdown-menu--category text-center">
        <?php if(Yii::$app->language === 'ru') : ?>
            <span class="dropdown-item active">RU</span>
            <a class="dropdown-item" href="<?= \yii\helpers\Url::base() . '/ua' ?>">UA</a>
        <?php else : ?>
            <a class="dropdown-item" href="<?= \yii\helpers\Url::base() . '/ru' ?>">RU</span>
            <span class="dropdown-item active">UA</a>
        <?php endif; ?>
    </div>
</li>