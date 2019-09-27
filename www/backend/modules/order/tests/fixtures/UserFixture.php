<?php
namespace backend\modules\order\tests\fixtures;

use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = 'common\models\User';
    public $dataFile = 'backend/modules/order/tests/_data/user.php';
}
