<?php
namespace backend\modules\blog\tests\fixtures\dbFixture;

use yii\test\ActiveFixture;

class TagLangFixture extends ActiveFixture 
{
    public $modelClass = 'backend\modules\blog\entities\TagLang';
    public $dataFile = 'backend/modules/blog/tests/_data/dbData/tag-lang.php';
}
