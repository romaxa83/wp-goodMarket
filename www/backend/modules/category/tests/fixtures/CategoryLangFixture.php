<?php
namespace backend\modules\category\tests\fixtures;

use yii\test\ActiveFixture;

class CategoryLangFixture extends ActiveFixture
{
    public $modelClass = 'backend\modules\category\models\CategoryLang';
    public $dataFile = 'backend/modules/category/tests/_data/category-lang.php';
    public $depends = [
        'backend\modules\category\tests\fixtures\LangFixture',
    ];
}
