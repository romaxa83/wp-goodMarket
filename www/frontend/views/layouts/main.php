<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php echo Html::csrfMetaTags(); ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>

        <?php echo Yii::$app->view->renderFile(Yii::getAlias('@app') . '/views/layouts/parts/header.php'); ?>

        <main class="main-content">
            <?= $content ?>
        </main>

        <?php echo Yii::$app->view->renderFile(Yii::getAlias('@app') . '/views/layouts/parts/footer.php'); ?>
        
        <?php echo Yii::$app->view->renderFile(Yii::getAlias('@app') . '/views/layouts/parts/popup.php'); ?>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>