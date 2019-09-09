<?php
namespace backend\modules\blog\tests\fixtures\dbFixture;

use yii\test\ActiveFixture;

class TagFixture extends ActiveFixture 
{
    public $modelClass = 'backend\modules\blog\entities\Tag';
    public $dataFile = 'backend/modules/blog/tests/_data/dbData/tag.php';
}
