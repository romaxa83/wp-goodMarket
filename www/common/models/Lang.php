<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Lang extends ActiveRecord {

    public static function tableName() {
        return 'lang';
    }

    public static function getDefaultLangID() {
        return Lang::find()->select(['id'])->where(['alias' => Yii::$app->params['settings']['mainContentLanguage']])->one()->id;
    }

    public static function getSelect2List($without = null) {
        $langList = Lang::find()->select(['id', 'name'])->where(['status' => TRUE])->asArray()->all();
        $langList = ArrayHelper::map($langList, 'id', 'name');
        return $langList;
    }


}
