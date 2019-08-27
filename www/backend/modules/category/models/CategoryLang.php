<?php

namespace backend\modules\category\models;

use common\models\Lang;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class CategoryLang extends ActiveRecord {

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-db-activerecord#tableName()-detail
     */
    public static function tableName() {
        return 'category_lang';
    }

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#rules()-detail
     * @return array
     */
    public function rules() {
        return [
            [['category_id', 'lang_id', 'name'], 'required'],
        ];
    }

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#attributeLabels()-detail
     * @return array
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'category_id' => 'Категория',
            'lang_id' => 'Язык',
            'name' => 'Название',
        ];
    }

    public function getLang() {
        return $this->hasOne(Lang::className(), ['id' => 'lang_id']);
    }

    public static function saveAll($category_id, $data) {
        $langs = Lang::find()->select(['id', 'alias'])->where(['status' => 1])->asArray()->all();
        $langs = ArrayHelper::map($langs, 'alias', 'id');
        foreach ($data as $key => $item) {
            if (!isset($langs[$key])) {
                continue;
            }
            $lang = self::find()->where(['category_id' => $category_id, 'lang_id' => $langs[$key]])->one();
            if (is_null($lang)) {
                $lang = new CategoryLang();
                $lang->category_id = $category_id;
            }
            $lang->attributes = $item;
            $lang->lang_id = $langs[$key];
            $lang->save();
        }
        return true;
    }

}
