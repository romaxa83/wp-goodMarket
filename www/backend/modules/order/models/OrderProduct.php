<?php

namespace backend\modules\order\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class OrderProduct extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'orders_products';
    }

    public function getOrderProduct($order_id, $product_id, $vproduct_id=0){
        return self::find()->select('*')->asArray()->where(['order_id'=>$order_id])->andWhere(['product_id'=>$product_id])->andWhere(['vproduct_id'=>$vproduct_id])->one();
    }

    public static function getDataByOrderID(int $order_id){
    	return self::find()->select('*')->where(['order_id'=>$order_id])->asArray()->all();

    }

    public static function getStockId($model, $id){
        $query = $model->className()::find()->select(['stock_id','import_id'])->where(['id'=>$id])->asArray()->one();
        return ($query['stock_id'] == null) ? $query['import_id'] : $query['stock_id'];
    }

    public static function saveProducts(int $order_id, string $products_data) {
        OrderProduct::deleteAll(['order_id' => $order_id]);
        $order_products_data = Json::decode($products_data);
        if (empty($order_products_data)) {
            Yii::$app->session->setFlash('error', 'В заказе отсутствуют товары');
            return false;
        }
        for ($i = 0; $i < count($order_products_data); $i++) {
            $product_id = $order_products_data[$i]['product_id'];
            $vproduct_id = $order_products_data[$i]['vproduct_id'];
            $order_product = new OrderProduct();
            unset($order_products_data[$i]['category_id']);
            foreach ($order_products_data[$i] as $key => $value) {
                $order_product->$key = $value;
            }
            $order_product->order_id = $order_id;
            if (!$order_product->save()){
                Yii::$app->session->setFlash('error', ArrayHelper::getColumn($order_product->errors, 0, false));
                return false;
            }
        }
        return true;
    }

    public static function getOrderCost(int $id = 0, array $products = []) {
        if ($id != 0) {
            $products = OrderProduct::getDataByOrderID($id);
        } else {
            if (empty($products)) {
                return 0;
            }
        }
        $order_summ = [];
        for ($i = 0; $i < count($products); $i++) {
            if (isset($order_summ[$products[$i]['currency']])) {
                $order_summ[$products[$i]['currency']] += ($products[$i]['count'] * $products[$i]['price']);
            } else {
                $order_summ[$products[$i]['currency']] = ($products[$i]['count'] * $products[$i]['price']);
            }
        }
        return $order_summ;
    }

    public static function getOrderCostStr(int $id = 0, array $products = []) {
        $order_summ = self::getOrderCost($id, $products);
        $summ_str = '';
        if (empty($order_summ)) {
            return $summ_str;
        }
        foreach ($order_summ as $k => $v) {
            $summ_str .= $v . ' ' . $k . '; ';
        }
        return $summ_str;
    }

}
