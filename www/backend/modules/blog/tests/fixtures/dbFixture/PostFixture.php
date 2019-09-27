<?php
namespace backend\modules\blog\tests\fixtures\dbFixture;

use yii\test\ActiveFixture;

class PostFixture extends ActiveFixture 
{
    public $modelClass = 'backend\modules\blog\entities\Post';
    public $dataFile = 'backend/modules/blog/tests/_data/dbData/post.php';
}
