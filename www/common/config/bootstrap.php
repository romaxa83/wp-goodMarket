<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@vendor', dirname(dirname(__DIR__)) . '/vendor');
Yii::setAlias('@root', dirname(dirname(__DIR__)));
Yii::setAlias('@image', '@backend' . '/web/img');
Yii::setAlias('@webroot', dirname(dirname(__DIR__)) . '/backend/web');
