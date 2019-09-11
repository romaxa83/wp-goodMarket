<?php
namespace backend\modules\blog\tests\fixtures\dbFixture;

use yii\test\ActiveFixture;

class PostLangFixture extends ActiveFixture 
{
    public $modelClass = 'backend\modules\blog\entities\PostLang';
    public $dataFile = 'backend/modules/blog/tests/_data/dbData/post-lang.php';
}
