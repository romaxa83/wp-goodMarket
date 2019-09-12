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

}
