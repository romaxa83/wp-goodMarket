<?php
namespace backend\modules\order\tests\fixtures;

use yii\test\ActiveFixture;

class OrderProductFixture extends ActiveFixture
{
    public $modelClass = 'backend\modules\order\models\OrderProduct';
    public $dataFile = 'backend/modules/order/tests/_data/order-product.php';
}
