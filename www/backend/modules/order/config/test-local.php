<?php
return yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../../../common/config/test-local.php',
    require Yii::getAlias('@backend') . '/config/main.php',
    require Yii::getAlias('@backend') . '/config/main-local.php',
    require Yii::getAlias('@backend') . '/config/test.php'
);
