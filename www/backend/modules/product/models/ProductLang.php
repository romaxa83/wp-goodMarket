<?php

namespace backend\modules\product\models;

use yii\db\ActiveRecord;
use common\models\Lang;
use backend\widgets\langwidget\LangWidget;
use backend\widgets\SeoWidget;

class ProductLang extends ActiveRecord {

    public $languageData;

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-db-activerecord#tableName()-detail
     */
    public static function tableName() {
        return 'product_lang';
    }

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#rules()-detail
     * @return array
     */
    public function rules() {
        return [
            [['product_id', 'lang_id', 'alias', 'name', 'description', 'price', 'currency'], 'required', 'message' => 'Необходимо заполнить '],
            ['alias', 'match', 'pattern' => '/^[a-z0-9_-]+$/', 'message' => 'Неверно введен алиас']
        ];
    }

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#attributeLabels()-detail
     * @return array
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'product_id' => 'Продукт',
            'lang_id' => 'Язык',
            'alias' => 'Алиас',
            'name' => 'Название',
            'description' => 'Описание',
            'price' => 'Цена',
            'currency' => 'Валюта'
        ];
    }

    public function getLang() {
        return $this->hasOne(Lang::className(), ['id' => 'lang_id']);
    }

    public function scenarios() {
        return [
            self::SCENARIO_DEFAULT => ['alias', 'name', 'description', 'price']
        ];
    }

    public static function saveAll($model, $modelLang, $data = []) {
        $success = FALSE;
        $modelLang->languageData = $data['ProductLang'];
        $LW = LangWidget::getActiveLanguageData(['id', 'alias']);
        $model->attributes = $data['Product'];
        $model->gallery = $data['Product']['gallery_serialize'];
        if ($model->validate() && LangWidget::validate($modelLang, $data)) {
            $model->save();
            if ($data['Product']['action'] === 'create') {
                $model->vendor_code = $model->id . sprintf("%'.03d", $model->category_id) . sprintf("%'.03d", $model->manufacturer_id);
                $model->save();
            }
            foreach ($LW as $item) {
                $lang = ProductLang::find()->where(['product_id' => $modelLang->product_id, 'lang_id' => $item['id']])->one();
                if ($lang === NULL) {
                    $lang = new ProductLang();
                }
                $lang->attributes = $data['ProductLang'][$item['alias']];
                $lang->product_id = $model->id;
                $lang->lang_id = $item['id'];
                if ($lang->validate()) {
                    $lang->save();
                }
            }
            SeoWidget::save($model->id, 'product', $data['SEO']);
            $success = TRUE;
        }
        return $success;
    }

}
