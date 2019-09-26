<?php
namespace backend\modules\order\tests\fixtures;

use yii\test\ActiveFixture;

class GuestFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Guest';
    public $dataFile = 'backend/modules/order/tests/_data/guest.php';
}
