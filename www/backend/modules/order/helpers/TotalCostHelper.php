<?php
namespace backend\modules\order\helpers;

use backend\modules\order\models\OrderProduct;
use common\helpers\ProductsHelper;

class TotalCostHelper
{
    public static function getTotalCost($order_id)
    {
        $total = 0;
        foreach (OrderProduct::find()->where(['order_id' => $order_id])->asArray()->all() as $one){
            $total += ($one['count'] * $one['price']);
        }
        return ProductsHelper::viewPrice($total);
    }
}