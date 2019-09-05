<?php

namespace backend\modules\product\models;

use backend\modules\stock\models\Stock;
use common\models\Lang;
use Yii;
use yii\db\ActiveRecord;
use backend\modules\stock\models\StocksProducts;
use yii\helpers\ArrayHelper;
use function foo\func;

class VProduct extends ActiveRecord {

    public $languageData;

    public static function tableName() {
        return 'vproduct';
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'media_id' => 'Изображение',
            'amount' => 'Кол-во',
            'price' => 'Цена',
            'char_value' => 'Вариации',
            'publish' => 'Опубликовать'
        ];
    }

    // если у вариативного товара нет цены устанавливает цену товара
    public static function correctVProductPriceAll($v_product_list) {
        if (empty($v_product_list)) {
            return [];
        }
        $product_id = [];
        foreach ($v_product_list as $key => $value) {
            $product_id[] = $value['product_id'];
        }
        $product_id = array_unique($product_id);

        $lang_id = Lang::find()->select(['id'])->where(['alias' => Yii::$app->params['settings']['defaultLanguage']])->one()->id;
        $product_price_data = ProductLang::find()->select(['product_id', 'price', 'currency'])->where(['lang_id' => $lang_id])->andWhere(['in', 'product_id', $product_id])->asArray()->all();
        $product_price_data = ArrayHelper::index($product_price_data, 'product_id');

        foreach ($v_product_list as $key => $value) {
            $v_product_list[$key]['price'] = ($value['price'] != 0 && !is_null($value['price'])) ? $value['price'] : $product_price_data[$value['product_id']]['price'];
        }

        return $v_product_list;
    }

    //    public function getVproductSale() {
//        if ($stock = $this->getStock()) {
//            return $stock['sale'];
//        }
//        return 0;
//    }
//    private function getStock() {
//        return StocksProducts::find()->where(['product_id' => $this->product_id])
//                        ->andWhere(['vproduct_id' => $this->stock_id])
//                        ->andWhere(['status' => 1])
//                        ->asArray()->one();
//    }
//
//    private function isActiveStock($stock_id) {
//        return Stock::find()->where(['id' => $stock_id])->andWhere(['status' => 1])->exists();
//    }
}
