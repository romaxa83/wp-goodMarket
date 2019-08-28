<?php

namespace backend\modules\product\models;

use backend\modules\stock\models\Stock;
use yii\db\ActiveRecord;
use backend\modules\stock\models\StocksProducts;

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
