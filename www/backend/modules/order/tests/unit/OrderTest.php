<?php

use backend\modules\order\models\Order;
use backend\modules\order\models\OrderProduct;
use backend\modules\order\tests\fixtures\OrderProductFixture;
use backend\modules\order\tests\fixtures\UserFixture;
use backend\modules\order\tests\fixtures\GuestFixture;
use backend\modules\order\tests\fixtures\OrderFixture;

use backend\modules\product\models\Product;
use Codeception\Test\Unit;
use common\models\Guest;
use common\models\User;
use yii\helpers\Json;

class OrderTest extends Unit {

    public $data = [
        'Order' => [
            'payment_method' => 3,
            'delivary' => 2,
            'city' => 'місто Черкаси, Черкаська область',
            'address' => 'Відділення №2: вул. Чигиринська, 11/1',
            'paid' => 1,
            'comment' => '<p><span class="label-red">Label</span></p>',
        ],

        'products_data' => [
            ["product_id" => "1", "lang_id" => "1", "vproduct_id" => "1", "category_id" => "1", "count" => "3", "product_price" => "1001", "price" => "1001", "currency" => "uah"],
            ["product_id" => "2", "lang_id" => "2", "vproduct_id" => "2", "category_id" => "2", "count" => "4", "product_price" => "1002", "price" => "1002", "currency" => "usd"]
        ]
    ];

    /**
     * @var \UnitTester
     */
    public $tester;

    /* подгрузка данных в тестовую бд, перед тестами */
    public function _before() {
        $this->tester->haveFixtures([
            'order' => [
                'class' => OrderFixture::className(),
            ],
            'order-product' => [
                'class' => OrderProductFixture::className(),
            ],
            'user' => [
                'class' => UserFixture::className(),
            ],
            'guest' => [
                'class' => GuestFixture::className(),
            ],
        ]);
    }

    public function _after() {
        OrderProduct::deleteAll();
        Order::deleteAll();
        Guest::deleteAll();
        User::deleteAll();
    }

    public function testCreateSuccessWithProductsByUser() {
        $data = $this->data;
        $data['Order']['user_id'] = User::find()->one()->id;
        $order = new Order();
        $this->assertTrue($order->load($data));
        $this->assertTrue($order->save());
        $this->assertTrue(OrderProduct::saveProducts($order->id, Json::encode($data['products_data'])));
    }

    public function testCreateSuccessWithProductsByGuest() {
        $data = $this->data;
        $data['Order']['guest_id'] = Guest::find()->one()->id;
        $order = new Order();
        $this->assertTrue($order->load($data));
        $this->assertTrue($order->save());
        $this->assertTrue(OrderProduct::saveProducts($order->id, Json::encode($data['products_data'])));
    }

    public function testEditWithProducts() {
        $data = $this->data;
        $data['Order']['user_id'] = User::find()->one()->id;
        $order = $this->tester->grabFixture('order', 1);

        $this->assertTrue($order->load($data));
        $this->assertTrue($order->save());
        $this->assertTrue(OrderProduct::saveProducts($order->id, Json::encode($data['products_data'])));

        expect($order->payment_method)->equals($data['Order']['payment_method']);
        expect($order->delivary)->equals($data['Order']['delivary']);
        expect($order->city)->equals($data['Order']['city']);
        expect($order->address)->equals($data['Order']['address']);
        expect($order->paid)->equals($data['Order']['paid']);
        expect($order->comment)->equals($data['Order']['comment']);

        $orderProduct = OrderProduct::find()->select(["product_id", "lang_id", "vproduct_id", "count", "product_price", "price", "currency"])
            ->where(['order_id' => $order->id])->asArray()->all();

        expect(count($orderProduct))->equals(2);

        foreach ($orderProduct as $k => $v) {
            foreach ($data['products_data'] as $k1 => $v1) {
                foreach ($v1 as $k2 => $v2) {
                    if (isset($orderProduct[$k1][$k2])) {
                        expect($orderProduct[$k1][$k2])->equals($v2);
                    }
                }
            }
        }
    }
}
