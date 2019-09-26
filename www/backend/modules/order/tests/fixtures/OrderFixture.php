<?php
namespace backend\modules\order\tests\fixtures;

use yii\test\ActiveFixture;

class OrderFixture extends ActiveFixture
{
    public $modelClass = 'backend\modules\order\models\Order';
    public $dataFile = 'backend/modules/order/tests/_data/order.php';
    public $depends = [
        'backend\modules\order\tests\fixtures\UserFixture',
        'backend\modules\order\tests\fixtures\GuestFixture',
        'backend\modules\order\tests\fixtures\OrderProductFixture',
    ];
}
