<?php

namespace backend\modules\order\models;

class OrderProduct extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'orders_products';
    }

    public function getOrderProduct($order_id, $product_id, $vproduct_id=0){
        return self::find()->select('*')->asArray()->where(['order_id'=>$order_id])->andWhere(['product_id'=>$product_id])->andWhere(['vproduct_id'=>$vproduct_id])->one();
    }

    public function getDataByOrderID($order_id){
    	return self::find()->select('*')->where(['order_id'=>$order_id])->asArray()->all();

    }

    public static function getStockId($model, $id){
        $query = $model->className()::find()->select(['stock_id','import_id'])->where(['id'=>$id])->asArray()->one();
        return ($query['stock_id'] == null) ? $query['import_id'] : $query['stock_id'];
    }

    public static function getOrderCost($id = 0, $products = []) {
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

    public static function getOrderCostStr($id = 0, $products = []) {
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
