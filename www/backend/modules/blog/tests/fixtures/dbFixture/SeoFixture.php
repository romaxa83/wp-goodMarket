<?php
namespace backend\modules\blog\tests\fixtures\dbFixture;

use yii\test\ActiveFixture;

class SeoFixture extends ActiveFixture 
{
    public $modelClass = 'backend\modules\blog\entities\Meta';
    public $dataFile = 'backend/modules/blog/tests/_data/dbData/meta.php';
}
