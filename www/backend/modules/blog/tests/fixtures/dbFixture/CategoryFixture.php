<?php
namespace backend\modules\blog\tests\fixtures\dbFixture;

use yii\test\ActiveFixture;

class CategoryFixture extends ActiveFixture 
{
    public $modelClass = 'backend\modules\blog\entities\Category';
    public $dataFile = 'backend/modules/blog/tests/_data/dbData/category.php';
}
