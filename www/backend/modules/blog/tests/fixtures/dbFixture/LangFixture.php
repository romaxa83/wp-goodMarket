<?php
namespace backend\modules\blog\tests\fixtures\dbFixture;

use yii\test\ActiveFixture;

class LangFixture extends ActiveFixture 
{
    public $modelClass = 'common\models\Lang';
    public $dataFile = 'backend/modules/blog/tests/_data/dbData/lang.php';
}