<?php

namespace  backend\modules\order\service;

use backend\modules\order\models\OrderProduct;
use backend\modules\product\models\Product;
use backend\modules\product\models\VProduct;
use backend\modules\stock\models\Stock;
use backend\modules\stock\models\StocksProducts;
use common\helpers\ProductsHelper;
use common\service\CacheProductService;
use yii\db\Query;
use backend\modules\order\models\Order;

class OrderService
{
    private $product_service;

    public function __construct(CacheProductService $product_service)
    {
        $this->product_service = $product_service;
    }

    /*
     * Метод записывает продукты в таблицу 'order_product'
     * из таблицы 'cart_items' длля конкретного пользователя
     * принимает id-пользователя и id-заказа
     */
    public function moveProductForUser($user_id,$order_id)
    {
        \Yii::$app->db->createCommand()->batchInsert(
            'orders_products',['order_id','product_id','vproduct_id','count','price','product_price'],
            array_map(function($item) use ($order_id) {
                return [
                    'order_id' => $order_id,
                    'product_id' => OrderProduct::getStockId(new Product(),$item['product_id']),
                    'vproduct_id' => $item['vproduct_id']??null,
                    'count' => $item['quantity'],
                    'price' => $this->getPriceWithSale(OrderProduct::getStockId(new Product(),$item['product_id']),$item['vproduct_id']),
                    'product_price' => $this->getPrice(OrderProduct::getStockId(new Product(),$item['product_id']),$item['vproduct_id']),
                ];
            },(new Query())->select('*')->from('cart_items')
                ->where(['user_id' => $user_id])->all())
        )->execute();

    }

    public function moveProductForGuest($arr_cart,$order_id)
    {

        \Yii::$app->db->createCommand()->batchInsert(
            'orders_products',['order_id','product_id','vproduct_id','count','price','product_price'],
            array_map(function($item) use ($order_id) {
                return [
                    'order_id' => $order_id,
                    'product_id' => OrderProduct::getStockId(new Product(),$item[0]) ,
                    'vproduct_id' => !empty($item[2])?$item[2]:0,
                    'count' => (int)$item[1],
                    'price' => $this->getPriceWithSale(OrderProduct::getStockId(new Product(),$item[0]),!empty($item[2])?$item[2]:null),
                    'product_price' => $this->getPrice(OrderProduct::getStockId(new Product(),$item[0]),!empty($item[2])?$item[2]:null),

                ];
            },$arr_cart)
        )->execute();
    }

    public function getOrderCountForUser($user_id)
    {
        return Order::find()->where(['user_id' => $user_id])->count();
    }

    public function clearCart($user_id) : void
    {
        \Yii::$app->db->createCommand()->delete('cart_items', ['user_id' => $user_id])->execute();
    }

    private function getPrice($product_id,$vproduct_id)
    {
        $p = Product::find()->where(['or',['stock_id' => $product_id],['import_id' => $product_id]])->one();

        if($vproduct_id){
            $vp = VProduct::find()->where(['stock_id' => $vproduct_id])->one();
            if($vp->price){
                return $vp->price;
            } else {
                return $p->price;
            }
        }
        return $p->price;
    }

    private function getPriceWithSale($product_id,$vproduct_id)
    {
        if($vproduct_id){

            $sale = StocksProducts::find()->where(['product_id' => $product_id])
                ->andWhere(['vproduct_id' => $vproduct_id])->asArray()->one();
        } else {
            $sale = StocksProducts::find()->where(['product_id' => $product_id])
                ->asArray()->one();
        }

        if($sale){
            if(Stock::find()->where(['id' => $sale['stock_id']])->andWhere(['status' => 1])->exists()){
                return ProductsHelper::priceWithSale($this->getPrice($product_id,$vproduct_id),$sale['sale']);
            }
        }
        return $this->getPrice($product_id,$vproduct_id);
    }
}