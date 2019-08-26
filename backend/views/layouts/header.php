<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">
    <?php echo Html::a('<span class="logo-mini">GM</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">

                        <?php echo Html::a('Выход', ['/site/logout'], ['data-method' => 'post']); ?>

                </li>
                <!-- <li><a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a></li> -->
            </ul>
        </div>
    </nav>
</header>
