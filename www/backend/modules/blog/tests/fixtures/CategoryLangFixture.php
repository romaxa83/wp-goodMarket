<?php

namespace backend\modules\blog\tests\fixtures;

use yii\test\ActiveFixture;

class CategoryLangFixture extends ActiveFixture 
{

    public $modelClass = 'backend\modules\blog\entities\CategoryLang';
    public $dataFile = 'backend/modules/blog/tests/_data/category-lang.php';
}
