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

    public function getVProductLang() {
        return $this->hasMany(VProductLang::className(), ['vproduct_id' => 'id']);
    }

    public static function getVProductsByProduct($product_id) {
        $v_product_list = VProduct::find()->where(['publish' => 1, 'product_id' => $product_id])->asArray()->all();
        $v_product_list = ArrayHelper::index($v_product_list, 'id');
        return $v_product_list;
    }

    public static function indexBy(array $data, string $column = 'id') {
        foreach ($data as $k => $v) {
            $vproduct = [];
            foreach ($v['vproducts'] as $k1 => $v1) {
                $vproduct[$v1[$column]] = $v1;
            }
            $data[$k]['vproducts'] = $vproduct;
        }
        return $data;
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
